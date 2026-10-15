<?php
/**
 * Promove Leandro para admin sem alterar senha nem outros dados.
 */
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'leandro@codar.local';
$user = DB::table('users')->whereRaw('lower(email) = ?', [strtolower($email)])->first();

if (! $user) {
    fwrite(STDERR, "user not found: {$email}\n");
    exit(1);
}

$before = $user->role;
DB::table('users')->where('id', $user->id)->update([
    'role' => 'admin',
    'updated_at' => now(),
]);

$after = DB::table('users')->where('id', $user->id)->value('role');
echo "id={$user->id}\n";
echo "email={$user->email}\n";
echo "name={$user->name}\n";
echo "role_before={$before}\n";
echo "role_after={$after}\n";
echo "OK leandro_admin\n";
