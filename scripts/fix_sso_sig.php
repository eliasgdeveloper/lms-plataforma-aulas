<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$laravelKey = (string) config('app.key');
echo 'laravel_key_len=' . strlen($laravelKey) . "\n";
echo 'laravel_prefix=' . substr(bin2hex($laravelKey), 0, 16) . "\n";

$envPath = __DIR__ . '/.env';
$key = '';
foreach (file($envPath) as $line) {
    $line = trim($line);
    if (str_starts_with($line, 'APP_KEY=')) {
        $key = trim(substr($line, 8), "\"'");
        break;
    }
}
echo 'raw_env=' . substr($key, 0, 20) . "...\n";
if (str_starts_with($key, 'base64:')) {
    $decoded = base64_decode(substr($key, 7), true);
    echo 'decoded_len=' . strlen((string) $decoded) . "\n";
    echo 'decoded_prefix=' . substr(bin2hex((string) $decoded), 0, 16) . "\n";
    echo 'match=' . ($decoded === $laravelKey ? 'yes' : 'no') . "\n";
    $arenaKey = $decoded;
} else {
    $arenaKey = $key;
    echo 'match_raw=' . ($key === $laravelKey ? 'yes' : 'no') . "\n";
}

$b64 = 'testpayload';
$sigL = hash_hmac('sha256', $b64, $laravelKey);
$sigA = hash_hmac('sha256', $b64, (string) $arenaKey);
echo 'sig_match=' . ($sigL === $sigA ? 'yes' : 'no') . "\n";

$src = file_get_contents(__DIR__ . '/public/arena-php/auth/sso_consume.php');
echo 'has_decode=' . (str_contains($src, 'base64:') ? 'yes' : 'no') . "\n";
