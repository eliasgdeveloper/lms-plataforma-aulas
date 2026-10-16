<?php
/**
 * Consome token SSO emitido pelo LMS (ArenaSsoController).
 * Mantém a sessão PLM da Arena sem depender do login manual.
 */
require_once __DIR__ . '/bootstrap.php';
require_once dirname(__DIR__) . '/desafio/progress_store.php';

function plm_sso_app_key(): string
{
    static $key = null;
    if ($key !== null) {
        return $key;
    }

    $envPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . '.env';
    $key = '';
    if (is_readable($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            if (strpos($line, 'APP_KEY=') === 0) {
                $key = trim(substr($line, 8));
                $key = trim($key, "\"'");
                break;
            }
        }
    }

    // Compatível com assinatura via env('APP_KEY') (string bruta do .env)
    // e também com config('app.key') (valor já decodificado).
    return $key;
}

function plm_sso_keys(): array
{
    $raw = plm_sso_app_key();
    $keys = [$raw];
    if (str_starts_with($raw, 'base64:')) {
        $decoded = base64_decode(substr($raw, 7), true);
        if ($decoded !== false && $decoded !== '') {
            $keys[] = $decoded;
        }
    }

    return array_values(array_unique($keys));
}

$token = isset($_GET['token']) ? (string) $_GET['token'] : '';
$parts = explode('.', $token, 2);
if (count($parts) !== 2) {
    http_response_code(400);
    echo 'Token SSO invalido.';
    exit;
}

[$b64, $sig] = $parts;
$ok = false;
foreach (plm_sso_keys() as $appKey) {
    if ($appKey !== '' && hash_equals(hash_hmac('sha256', $b64, $appKey), $sig)) {
        $ok = true;
        break;
    }
}
if (! $ok) {
    http_response_code(403);
    echo 'Assinatura SSO invalida.';
    exit;
}

$pad = strlen($b64) % 4;
if ($pad > 0) {
    $b64 .= str_repeat('=', 4 - $pad);
}
$json = base64_decode(strtr($b64, '-_', '+/'), true);
$payload = $json ? json_decode($json, true) : null;

if (!is_array($payload)
    || empty($payload['u'])
    || empty($payload['exp'])
    || (int) $payload['exp'] < time()
) {
    http_response_code(403);
    echo 'Token SSO expirado ou incompleto.';
    exit;
}

$user = plm_find_user((string) $payload['u']);
if ($user === null) {
    http_response_code(403);
    echo 'Usuario Arena nao encontrado.';
    exit;
}

plm_login_success(array(
    'username' => $user['username'],
    'name' => isset($payload['n']) && $payload['n'] !== '' ? $payload['n'] : (isset($user['name']) ? $user['name'] : $user['username']),
    'role' => isset($payload['r']) && $payload['r'] !== '' ? $payload['r'] : (isset($user['role']) ? $user['role'] : ''),
));
desafio_progress_load_into_session();

$to = isset($payload['to']) ? (string) $payload['to'] : plm_url('desafio.php');
if (strpos($to, '/arena-php/') !== 0) {
    $to = plm_url('desafio.php');
}

header('Location: ' . $to);
exit;
