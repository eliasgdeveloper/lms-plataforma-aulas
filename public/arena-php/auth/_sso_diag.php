<?php
/**
 * Diagnóstico temporário SSO — remover após validação.
 */
header('Content-Type: text/plain; charset=utf-8');

$authDir = __DIR__;
$envCandidates = [
    dirname($authDir, 3) . DIRECTORY_SEPARATOR . '.env',
    dirname($authDir, 2) . DIRECTORY_SEPARATOR . '.env',
    dirname($authDir, 4) . DIRECTORY_SEPARATOR . '.env',
];

echo "SCRIPT_NAME=" . ($_SERVER['SCRIPT_NAME'] ?? '') . "\n";
echo "HTTPS=" . ($_SERVER['HTTPS'] ?? '') . "\n";
echo "COOKIE=" . (isset($_COOKIE['PLM_SESS']) ? 'present' : 'absent') . "\n";

foreach ($envCandidates as $i => $path) {
    echo "env[$i]=$path readable=" . (is_readable($path) ? 'yes' : 'no') . "\n";
}

require_once __DIR__ . '/bootstrap.php';
plm_start_session();
echo "plm_logged=" . (plm_is_logged_in() ? 'yes' : 'no') . "\n";
echo "base_path=" . plm_base_path() . "\n";
echo "session_name=" . session_name() . "\n";
echo "session_id=" . session_id() . "\n";
