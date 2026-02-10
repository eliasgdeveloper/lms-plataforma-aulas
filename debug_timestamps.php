<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

echo "═══════════════════════════════════════════════════════════\n";
echo "  DEBUG DETALHADO - PROBLEMA DE TIMESTAMPS NULL\n";
echo "═══════════════════════════════════════════════════════════\n\n";

// Simular request autenticado
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::create('/admin/usuarios/4', 'GET');
$request->setUserResolver(fn () => \App\Models\User::find(1));

echo "📋 PASSO 1: Verificar User direto do banco\n";
echo "──────────────────────────────────────────────────────────\n";

$userDirect = \App\Models\User::find(4);
echo "User ID 4 encontrado: " . ($userDirect ? 'SIM' : 'NÃO') . "\n";
echo "created_at tipo: " . gettype($userDirect->created_at) . "\n";
echo "created_at value: " . var_export($userDirect->created_at, true) . "\n";
echo "created_at null? " . ($userDirect->created_at === null ? 'SIM' : 'NÃO') . "\n";

if ($userDirect->created_at) {
    echo "created_at->format(): " . $userDirect->created_at->format('d/m/Y H:i') . "\n";
}
echo "\n";

// ────────────────────────────────────────────────────────────

echo "📋 PASSO 2: Simular Route Model Binding (como o controller recebe)\n";
echo "──────────────────────────────────────────────────────────\n";

// Route model binding usa implicitly bound model
$routeParam = 4;
$userViaBinding = \App\Models\User::findOrFail($routeParam);

echo "User via binding encontrado: " . ($userViaBinding ? 'SIM' : 'NÃO') . "\n";
echo "created_at tipo: " . gettype($userViaBinding->created_at) . "\n";
echo "created_at null? " . ($userViaBinding->created_at === null ? 'SIM' : 'NÃO') . "\n";

if ($userViaBinding->created_at) {
    echo "Pode fazer format? SIM - " . $userViaBinding->created_at->format('d/m/Y H:i') . "\n";
}
echo "\n";

// ────────────────────────────────────────────────────────────

echo "📋 PASSO 3: Testar Controller Show Method\n";
echo "──────────────────────────────────────────────────────────\n";

try {
    auth()->setUser(\App\Models\User::find(1));
    
    $controller = new \App\Http\Controllers\Admin\UserController();
    $user = \App\Models\User::findOrFail(4);
    
    // Simular exatamente o que o controller faz
    $activityLogs = \App\Models\AuditLog::byUser($user->id)
        ->latest()
        ->limit(10)
        ->get();
    
    echo "Controller consegue carregar user: SIM\n";
    echo "created_at no user do controller: " . var_export($user->created_at, true) . "\n";
    echo "created_at null? " . ($user->created_at === null ? 'SIM' : 'NÃO') . "\n";
    
    if ($user->created_at) {
        echo "Pode fazer format no controller? SIM - " . $user->created_at->format('d/m/Y H:i') . "\n";
    }
    
    // Agora tenta renderizar a view exatamente como o controller faz
    echo "\nTentando renderizar view...\n";
    $html = view('pages.admin_usuarios.show', [
        'user' => $user,
        'activityLogs' => $activityLogs,
    ])->render();
    
    echo "View renderizou com sucesso!\n";
    
} catch (\Exception $e) {
    echo "❌ ERRO ao renderizar view:\n";
    echo "   Mensagem: " . $e->getMessage() . "\n";
    echo "   Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\n   Stack Trace:\n";
    foreach (array_slice(explode("\n", $e->getTraceAsString()), 0, 5) as $line) {
        echo "   " . $line . "\n";
    }
}

echo "\n";

// ────────────────────────────────────────────────────────────

echo "📋 PASSO 4: Verificar User Model Attributes\n";
echo "──────────────────────────────────────────────────────────\n";

$user = \App\Models\User::find(4);
echo "User attributes:\n";
print_r($user->getAttributes());

echo "\nUser casts:\n";
print_r($user->getCasts());

echo "\nUser dates (timestamps):\n";
print_r($user->getDates());

echo "\nTimestamps habilitados?\n";
echo "(\$timestamps property): " . (property_exists($user, 'timestamps') ? 'SIM' : 'NÃO') . "\n";
echo "(getTimestampName): " . ($user->getCreatedAtColumn() ?? 'null') . "\n";

echo "\n";

// ────────────────────────────────────────────────────────────

echo "📋 PASSO 5: Verificar Banco de Dados\n";
echo "──────────────────────────────────────────────────────────\n";

$rawUser = \Illuminate\Support\Facades\DB::select(
    'SELECT id, name, created_at, updated_at FROM users WHERE id = ?',
    [4]
);

if ($rawUser) {
    echo "Query bruta do banco:\n";
    print_r($rawUser[0]);
} else {
    echo "Usuário não encontrado no banco!\n";
}

echo "\n";

// ────────────────────────────────────────────────────────────

echo "📋 PASSO 6: Verificar Middleware RoleMiddleware\n";
echo "──────────────────────────────────────────────────────────\n";

$middleware = new \App\Http\Middleware\RoleMiddleware();
echo "Middleware classe: " . get_class($middleware) . "\n";

// Ler o arquivo do middleware
$middlewareFile = file_get_contents(__DIR__ . '/app/Http/Middleware/RoleMiddleware.php');
if (strpos($middlewareFile, 'unset') !== false || strpos($middlewareFile, 'forget') !== false) {
    echo "⚠️  AVISO: Middleware pode estar removendo atributos do user!\n";
} else {
    echo "Middleware parece OK (não remove atributos)\n";
}

echo "\n═══════════════════════════════════════════════════════════\n";
