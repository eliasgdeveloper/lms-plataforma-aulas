<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Criar usuários de teste com diferentes roles
$users = [
    ['name' => 'Admin User', 'email' => 'admin@example.com', 'role' => 'admin'],
    ['name' => 'Professor User', 'email' => 'professor@example.com', 'role' => 'professor'],
    ['name' => 'Aluno User', 'email' => 'aluno@example.com', 'role' => 'aluno'],
];

foreach ($users as $userData) {
    $user = User::where('email', $userData['email'])->first();
    
    if ($user) {
        // Atualizar role se existir
        $user->update(['role' => $userData['role']]);
        echo "✓ Atualizado: {$userData['email']} - role: {$userData['role']}\n";
    } else {
        // Criar novo usuário
        User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make('password'),
            'role' => $userData['role'],
            'email_verified_at' => now(),
        ]);
        echo "✓ Criado: {$userData['email']} - role: {$userData['role']}\n";
    }
}

echo "\nUsuários para testar:\n";
User::select('id', 'name', 'email', 'role')->get()->each(function ($user) {
    echo "- {$user->email} (role: {$user->role})\n";
});
