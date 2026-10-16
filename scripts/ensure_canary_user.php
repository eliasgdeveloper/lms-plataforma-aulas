<?php
/**
 * Garante usuário canário de testes (não altera Elias/Leandro).
 * Login: userTesteAI | Senha: CanarioAI#2026
 */
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

$email = 'usertesteai@codar.local';
$password = getenv('CANARY_USER_PASSWORD');

if ($password === false || $password === '') {
    fwrite(STDERR, "Defina CANARY_USER_PASSWORD no ambiente antes de executar este script.\n");
    exit(1);
}

$user = User::query()->whereRaw('lower(email) = ?', [strtolower($email)])->first();
$payload = [
    'name' => 'userTesteAI',
    'email' => $email,
    'password' => Hash::make($password),
    'role' => 'aluno',
    'email_verified_at' => now(),
];
if (Schema::hasColumn('users', 'status')) {
    $payload['status'] = 'active';
}

if ($user) {
    $user->forceFill($payload)->save();
    echo "updated user id={$user->id}\n";
} else {
    $user = User::create($payload);
    echo "created user id={$user->id}\n";
}

$cursoId = 3;
$table = Schema::hasTable('matriculas') ? 'matriculas' : (Schema::hasTable('enrollments') ? 'enrollments' : null);
if (! $table) {
    fwrite(STDERR, "no enrollments/matriculas table\n");
    exit(1);
}

$enrolled = DB::table($table)
    ->where('aluno_id', $user->id)
    ->where('curso_id', $cursoId)
    ->exists();

if (! $enrolled) {
    $row = [
        'aluno_id' => $user->id,
        'curso_id' => $cursoId,
    ];
    if (Schema::hasColumn($table, 'status')) {
        $row['status'] = 'ativo';
    }
    if (Schema::hasColumn($table, 'data_matricula')) {
        $row['data_matricula'] = now()->toDateString();
    }
    if (Schema::hasColumn($table, 'created_at')) {
        $row['created_at'] = now();
        $row['updated_at'] = now();
    }
    DB::table($table)->insert($row);
    echo "enrolled curso {$cursoId} via {$table}\n";
} else {
    echo "already enrolled curso {$cursoId} via {$table}\n";
}

echo "login={$email}\n";
echo "alias=userTesteAI\n";
echo "password={$password}\n";
echo "OK canary_user\n";
