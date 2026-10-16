<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$kernel->handle($request);

echo "Tabelas no banco de dados:\n";
echo "==========================\n";

$tables = \Illuminate\Support\Facades\Schema::getTables();
foreach ($tables as $table) {
    echo "  ✓ " . $table['name'] . "\n";
}

echo "\nVerificação específica:\n";
echo "=======================\n";
echo "Tabela 'payments' existe? " . (\Illuminate\Support\Facades\Schema::hasTable('payments') ? 'SIM' : 'NÃO') . "\n";
echo "Tabela 'pagamentos' existe? " . (\Illuminate\Support\Facades\Schema::hasTable('pagamentos') ? 'SIM' : 'NÃO') . "\n";

if (\Illuminate\Support\Facades\Schema::hasTable('pagamentos')) {
    echo "\nColunas na tabela 'pagamentos':\n";
    $columns = \Illuminate\Support\Facades\Schema::getColumns('pagamentos');
    foreach ($columns as $column) {
        echo "  - " . $column['name'] . "\n";
    }
}
