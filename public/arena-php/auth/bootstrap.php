<?php

if (!defined('PLM_ROOT')) {
    define('PLM_ROOT', dirname(__DIR__));
}

function plm_config()
{
    static $config = null;
    if ($config === null) {
        $config = require __DIR__ . '/config.php';
    }
    return $config;
}

function plm_base_path()
{
    // Path público canônico no domínio (Hostinger reescreve public/arena-php → /arena-php).
    // Nunca derivar de SCRIPT_NAME: em CLI/rewrite pode virar "public/arena-php/" e o cookie não cola.
    return '/arena-php/';
}

function plm_url($relative)
{
    return plm_base_path() . ltrim($relative, '/');
}

function plm_start_session()
{
    if (session_id() !== '') {
        return;
    }

    $config = plm_config();
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $lifetime = ((int) $config['session_hours']) * 3600;

    // SameSite=None + Secure: cookie funciona dentro do iframe do LMS no mesmo domínio
    // (Chrome trata navegação em iframe de forma mais restrita com Lax).
    $sameSite = $secure ? 'None' : 'Lax';

    session_name($config['session_name']);
    session_set_cookie_params(array(
        'lifetime' => $lifetime,
        'path' => '/arena-php/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => $sameSite,
    ));
    session_start();
}

function plm_csrf_token()
{
    plm_start_session();
    if (empty($_SESSION['plm_csrf'])) {
        $_SESSION['plm_csrf'] = bin2hex(function_exists('random_bytes') ? random_bytes(16) : openssl_random_pseudo_bytes(16));
    }
    return $_SESSION['plm_csrf'];
}

function plm_csrf_ok($token)
{
    plm_start_session();
    return is_string($token)
        && isset($_SESSION['plm_csrf'])
        && hash_equals($_SESSION['plm_csrf'], $token);
}

function plm_flash($message)
{
    plm_start_session();
    $_SESSION['plm_flash'] = $message;
}

function plm_consume_flash()
{
    plm_start_session();
    if (empty($_SESSION['plm_flash'])) {
        return '';
    }
    $message = $_SESSION['plm_flash'];
    unset($_SESSION['plm_flash']);
    return $message;
}

function plm_too_many_attempts()
{
    plm_start_session();
    $config = plm_config();
    $lockedUntil = isset($_SESSION['plm_locked_until']) ? (int) $_SESSION['plm_locked_until'] : 0;
    return $lockedUntil > time();
}

function plm_register_fail()
{
    plm_start_session();
    $config = plm_config();
    $attempts = isset($_SESSION['plm_attempts']) ? (int) $_SESSION['plm_attempts'] : 0;
    $attempts++;
    $_SESSION['plm_attempts'] = $attempts;
    if ($attempts >= (int) $config['max_attempts']) {
        $_SESSION['plm_locked_until'] = time() + (int) $config['lock_seconds'];
        $_SESSION['plm_attempts'] = 0;
    }
}

function plm_clear_attempts()
{
    unset($_SESSION['plm_attempts'], $_SESSION['plm_locked_until']);
}

function plm_is_logged_in()
{
    plm_start_session();
    return !empty($_SESSION['plm_user']['username']);
}

/**
 * Se o aluno já está no LMS mas a sessão da Arena sumiu (iframe/cookie),
 * redireciona para o SSO do Laravel em vez de pedir login de novo.
 */
function plm_require_login_or_lms_sso($fallbackRelative = 'desafio.php')
{
    if (plm_is_logged_in()) {
        return;
    }

    $requestUri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
    $to = $requestUri !== '' ? $requestUri : (plm_base_path() . ltrim($fallbackRelative, '/'));

    // Só aceita destino dentro da Arena
    if (strpos($to, '/arena-php/') !== 0) {
        $to = plm_base_path() . ltrim($fallbackRelative, '/');
    }

    header('Location: /aluno/arena/entrar?to=' . rawurlencode($to));
    exit;
}

function plm_current_user()
{
    plm_start_session();
    return isset($_SESSION['plm_user']) ? $_SESSION['plm_user'] : null;
}

function plm_login_success($user)
{
    plm_start_session();
    session_regenerate_id(true);
    plm_clear_attempts();
    $_SESSION['plm_user'] = array(
        'username' => $user['username'],
        'name' => isset($user['name']) ? $user['name'] : $user['username'],
        'role' => isset($user['role']) ? $user['role'] : '',
    );
}

function plm_logout()
{
    plm_start_session();
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], isset($params['domain']) ? $params['domain'] : '', $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function plm_find_user($username)
{
    $username = trim($username);
    if ($username === '') {
        return null;
    }

    $config = plm_config();
    if (!empty($config['use_database'])) {
        return plm_find_user_db($username, $config);
    }

    $localUsersFile = __DIR__ . '/users.local.php';
    $users = is_file($localUsersFile)
        ? require $localUsersFile
        : require __DIR__ . '/users.php';
    foreach ($users as $user) {
        if (strcasecmp($user['username'], $username) === 0) {
            return $user;
        }
    }
    return null;
}

function plm_find_user_db($username, $config)
{
    $db = $config['db'];
    $dsn = 'mysql:host=' . $db['host'] . ';dbname=' . $db['name'] . ';charset=' . $db['charset'];

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ));
    } catch (PDOException $exception) {
        plm_flash('Não foi possível conectar ao banco de dados. Confira os dados em auth/config.php.');
        return null;
    }

    $sql = 'SELECT username, nome AS name, cargo AS role, password_hash
            FROM `' . str_replace('`', '', $db['table']) . '`
            WHERE username = :username AND ativo = 1
            LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':username' => $username));
    $user = $stmt->fetch();
    return $user ? $user : null;
}
