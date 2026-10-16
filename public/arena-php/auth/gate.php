<?php
require_once __DIR__ . '/bootstrap.php';
plm_start_session();

if (!plm_is_logged_in()) {
    header('Location: ' . plm_url('index.php'));
    exit;
}

$relative = isset($_GET['file']) ? $_GET['file'] : '';
$relative = str_replace('\\', '/', $relative);
$relative = ltrim($relative, '/');

if ($relative === '' || strpos($relative, '..') !== false) {
    http_response_code(400);
    exit('Requisição inválida.');
}

$blocked = array('.php', '.sql', '.htaccess', '.env', '.gitignore', '.ini');
$lower = strtolower($relative);
foreach ($blocked as $suffix) {
    if (substr($lower, -strlen($suffix)) === $suffix || strpos($lower, 'auth/') === 0) {
        http_response_code(403);
        exit('Acesso negado.');
    }
}

$root = str_replace('\\', '/', realpath(PLM_ROOT));
$candidate = $root . '/' . $relative;
$target = realpath($candidate);

if ($target === false) {
    http_response_code(404);
    exit('Arquivo não encontrado.');
}

$targetNormalized = str_replace('\\', '/', $target);
if (strpos($targetNormalized, $root) !== 0) {
    http_response_code(403);
    exit('Acesso negado.');
}

if (is_dir($target)) {
    foreach (array('Index.html', 'index.html', 'index.php') as $indexName) {
        $maybe = $target . DIRECTORY_SEPARATOR . $indexName;
        if (is_file($maybe)) {
            $target = $maybe;
            $targetNormalized = str_replace('\\', '/', realpath($target));
            break;
        }
    }
}

if (!is_file($target)) {
    http_response_code(404);
    exit('Arquivo não encontrado.');
}

$extension = strtolower(pathinfo($target, PATHINFO_EXTENSION));
$types = array(
    'html' => 'text/html; charset=UTF-8',
    'htm' => 'text/html; charset=UTF-8',
    'css' => 'text/css; charset=UTF-8',
    'js' => 'application/javascript; charset=UTF-8',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'webp' => 'image/webp',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
);

header('Content-Type: ' . (isset($types[$extension]) ? $types[$extension] : 'application/octet-stream'));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($target);
exit;
