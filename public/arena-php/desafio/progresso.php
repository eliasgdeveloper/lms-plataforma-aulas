<?php
require_once dirname(__DIR__) . '/auth/bootstrap.php';
require_once __DIR__ . '/progress_store.php';
plm_start_session();

header('Content-Type: application/json; charset=UTF-8');

if (!plm_is_logged_in()) {
    http_response_code(401);
    echo json_encode(array('ok' => false));
    exit;
}

$key = desafio_session_key();
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    $_SESSION[$key] = desafio_progress_defaults();
    desafio_progress_save_from_session();
} else {
    desafio_progress_boot();
}

$etapasFile = defined('DESAFIO_ETAPAS_FILE') ? DESAFIO_ETAPAS_FILE : (__DIR__ . '/etapas.php');
$etapas = require $etapasFile;
$state = desafio_progress_state_ref();
$unlocked = true;
foreach ($etapas as $item) {
    if (!in_array((int) $item['id'], $state['concluidas'], true)) {
        $unlocked = false;
        break;
    }
}

$user = plm_current_user();
$uname = isset($user['username']) ? (string) $user['username'] : '';
$urole = isset($user['role']) ? (string) $user['role'] : '';
if (strcasecmp($uname, 'Elias') === 0 || ($urole !== '' && stripos($urole, 'Professor') !== false)) {
    $unlocked = true;
}

echo json_encode(array(
    'ok' => true,
    'pontos' => (int) $state['pontos'],
    'concluidas' => $state['concluidas'],
    'surpresas' => $state['surpresas'],
    'avatar' => $state['avatar'],
    'extra_done' => !empty($state['extra_done']),
    'extra_unlocked' => $unlocked,
    'saved' => true,
), JSON_UNESCAPED_UNICODE);