<?php
// Script de debug para executar via tinker

$script = <<<'PHP'
echo "\n═══════════════════════════════════════════════════════════\n";
echo "  DEBUG DETALHADO - PROBLEMA DE TIMESTAMPS NULL\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// PASSO 1
echo "📋 PASSO 1: User direto do banco\n";
echo "──────────────────────────────────────────────────────────\n";

$userDirect = App\Models\User::find(4);
if ($userDirect) {
    echo "✅ User encontrado\n";
    echo "   ID: " . $userDirect->id . "\n";
    echo "   Name: " . $userDirect->name . "\n";
    echo "   created_at tipo: " . gettype($userDirect->created_at) . "\n";
    echo "   created_at null? " . ($userDirect->created_at === null ? 'SIM ❌' : 'NÃO ✅') . "\n";
    if ($userDirect->created_at) {
        echo "   created_at valor: " . $userDirect->created_at . "\n";
    }
} else {
    echo "❌ User não encontrado\n";
}
echo "\n";

// PASSO 2
echo "📋 PASSO 2: User com query filtrada\n";
echo "──────────────────────────────────────────────────────────\n";

$userFiltered = App\Models\User::select('id', 'name', 'email', 'created_at', 'updated_at')->find(4);
if ($userFiltered) {
    echo "✅ User encontrado\n";
    echo "   created_at null? " . ($userFiltered->created_at === null ? 'SIM ❌' : 'NÃO ✅') . "\n";
}
echo "\n";

// PASSO 3
echo "📋 PASSO 3: User Model - Verificar configuração\n";
echo "──────────────────────────────────────────────────────────\n";

$dummy = new App\Models\User();
echo "   Timestamps habilitados? " . ($dummy->timestamps ? 'SIM ✅' : 'NÃO ❌') . "\n";
echo "   Coluna created_at: " . $dummy->getCreatedAtColumn() . "\n";
echo "   Coluna updated_at: " . $dummy->getUpdatedAtColumn() . "\n";

echo "\n";

// PASSO 4
echo "📋 PASSO 4: Raw Database Query\n";
echo "──────────────────────────────────────────────────────────\n";

$raw = DB::select('SELECT id, name, created_at, updated_at FROM users WHERE id = 4');
if ($raw) {
    echo "✅ Dados no banco:\n";
    echo "   ID: " . $raw[0]->id . "\n";
    echo "   Name: " . $raw[0]->name . "\n";
    echo "   created_at: " . $raw[0]->created_at . "\n";
} else {
    echo "❌ Nenhum dado encontrado\n";
}
echo "\n";

// PASSO 5
echo "📋 PASSO 5: Teste de renderização de view SIMPLES\n";
echo "──────────────────────────────────────────────────────────\n";

try {
    $user = App\Models\User::find(4);
    
    // Renderizar apenas um snippet simples da view
    $blade = "@php
    echo 'User: ' . \$user->name . chr(10);
    echo 'created_at type: ' . gettype(\$user->created_at) . chr(10);
    echo 'created_at value: ' . var_export(\$user->created_at, true) . chr(10);
    if (\$user->created_at) {
        echo 'Formatted: ' . \$user->created_at->format('d/m/Y H:i') . chr(10);
    }
    @endphp";
    
    echo view(\Illuminate\Support\Facades\View::make('strings.php', ['view' => $blade], null))
        ->with('user', $user)
        ->render();
    
} catch (\Exception $e) {
    echo "❌ Erro ao renderizar: " . $e->getMessage() . "\n";
}

echo "\n═══════════════════════════════════════════════════════════\n";
PHP;

// Escrever em arquivo temporário
file_put_contents(__DIR__ . '/debug_script.php', $script);

echo "Script  `+$script+` criado\n";
echo "\nExecutando...\n";
passthru('cd "' . __DIR__ . '" && php artisan tinker < debug_script.php');
