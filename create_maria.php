<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Maria',
    'email' => 'maria@teste.com',
    'password' => Hash::make('password'),
    'role' => 'aluno',
    'email_verified_at' => now(),
]);

echo "✓ Usuário maria@teste.com criado com sucesso!\nSenha: password\n";
