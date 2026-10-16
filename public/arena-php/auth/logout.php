<?php
require_once __DIR__ . '/bootstrap.php';
plm_logout();
header('Location: ' . plm_url('index.php'));
exit;
