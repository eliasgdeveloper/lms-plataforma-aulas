<?php
require_once __DIR__ . '/bootstrap.php';
require_once dirname(__DIR__) . '/desafio/progress_store.php';
plm_start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . plm_url('index.php'));
    exit;
}

if (!plm_csrf_ok(isset($_POST['csrf']) ? $_POST['csrf'] : '')) {
    plm_flash('Sessão expirada. Atualize a página e tente novamente.');
    header('Location: ' . plm_url('index.php'));
    exit;
}

if (plm_too_many_attempts()) {
    plm_flash('Muitas tentativas. Aguarde um minuto e tente de novo.');
    header('Location: ' . plm_url('index.php'));
    exit;
}

$username = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$password = isset($_POST['senha']) ? $_POST['senha'] : '';

$user = plm_find_user($username);
if ($user === null && !empty($_SESSION['plm_flash'])) {
    header('Location: ' . plm_url('index.php'));
    exit;
}

$valid = $user && isset($user['password_hash']) && password_verify($password, $user['password_hash']);

if (!$valid) {
    plm_register_fail();
    plm_flash('Usuário ou senha inválidos.');
    header('Location: ' . plm_url('index.php'));
    exit;
}

plm_login_success($user);
desafio_progress_load_into_session();
header('Location: ' . plm_url('bemvindo.php'));
exit;
