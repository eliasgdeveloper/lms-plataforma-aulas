<?php
/**
 * Exporta dados do SQLite local para importar no MySQL da Hostinger.
 * Uso: php scripts/export_lms_dump.php
 */
$root = dirname(__DIR__);
$db = new PDO('sqlite:' . $root . '/database/database.sqlite');
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$tables = [
    'users',
    'cursos',
    'aulas',
    'conteudos',
    'matriculas',
    'conteudo_progresso',
];

$dump = [
    'exported_at' => date('c'),
    'tables' => [],
];

foreach ($tables as $table) {
    $cols = [];
    foreach ($db->query("PRAGMA table_info($table)") as $c) {
        $cols[] = $c['name'];
    }
    $rows = $db->query("SELECT * FROM $table")->fetchAll();
    // limpa chaves numéricas duplicadas do PDO
    $clean = [];
    foreach ($rows as $row) {
        $item = [];
        foreach ($cols as $col) {
            $item[$col] = $row[$col] ?? null;
        }
        $clean[] = $item;
    }
    $dump['tables'][$table] = [
        'columns' => $cols,
        'rows' => $clean,
    ];
    echo $table, ': ', count($clean), " linhas\n";
}

$out = $root . '/DEPLOY/lms-data-dump.json';
file_put_contents($out, json_encode($dump, JSON_UNESCAPED_UNICODE));
echo "Wrote $out (", round(filesize($out) / 1048576, 2), " MB)\n";
