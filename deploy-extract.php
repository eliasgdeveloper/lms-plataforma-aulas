<?php
/**
 * Extrator do LMS na Hostinger.
 * 1) Apague (ou ignore) os arquivos quebrados com "\" no nome.
 * 2) Suba este arquivo + DEPLOY-LMS-Codar.zip para public_html.
 * 3) Abra no navegador: https://cursosdecodar.com.br/deploy-extract.php
 * 4) Depois APAGUE este arquivo.
 */

declare(strict_types=1);
set_time_limit(0);
ini_set('memory_limit', '1024M');
ini_set('display_errors', '1');
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

$root = __DIR__;
$zipName = 'DEPLOY-LMS-Codar.zip';
$zipPath = $root . DIRECTORY_SEPARATOR . $zipName;
$keepDirs = ['moodle']; // não mexer no Moodle

echo '<h1>Extrator LMS Codar</h1><pre>';

if (! class_exists('ZipArchive')) {
    echo "ERRO: ZipArchive não está habilitado neste PHP.\n";
    echo "No hPanel: Avançado → Configuração PHP → ative zip.\n";
    echo '</pre>';
    exit;
}

if (! is_file($zipPath)) {
    echo "ERRO: não achei {$zipName} em public_html.\n";
    echo "Suba o ZIP para a mesma pasta deste script.\n";
    echo "Pasta atual: {$root}\n";
    echo '</pre>';
    exit;
}

echo "ZIP encontrado: {$zipName} (" . round(filesize($zipPath) / 1048576, 1) . " MB)\n";

// 1) Remover arquivos/pastas quebrados com barra invertida no nome
$deletedBroken = 0;
foreach (scandir($root) ?: [] as $item) {
    if ($item === '.' || $item === '..') {
        continue;
    }
    if (! str_contains($item, '\\')) {
        continue;
    }
    $path = $root . DIRECTORY_SEPARATOR . $item;
    if (is_file($path) || is_link($path)) {
        if (@unlink($path)) {
            $deletedBroken++;
            echo "Apagado (quebrado): {$item}\n";
        }
    } elseif (is_dir($path)) {
        // raro, mas tenta
        @rmdir($path);
        $deletedBroken++;
        echo "Apagado dir quebrado: {$item}\n";
    }
}
echo "Arquivos quebrados removidos: {$deletedBroken}\n\n";

// 2) Extrair ZIP com caminhos /
$zip = new ZipArchive();
$open = $zip->open($zipPath);
if ($open !== true) {
    echo "ERRO ao abrir ZIP. Código: {$open}\n</pre>";
    exit;
}

echo "Extraindo {$zip->numFiles} itens...\n";
$ok = 0;
$fail = 0;
for ($i = 0; $i < $zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);
    if ($name === false) {
        continue;
    }

    // normaliza
    $name = str_replace('\\', '/', $name);
    if ($name === '' || str_ends_with($name, '/')) {
        $dir = $root . '/' . rtrim($name, '/');
        if ($name !== '' && ! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        continue;
    }

    // não sobrescrever pasta moodle
    $top = explode('/', $name)[0] ?? '';
    if (in_array($top, $keepDirs, true)) {
        continue;
    }

    $target = $root . '/' . $name;
    $dir = dirname($target);
    if (! is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $stream = $zip->getStream($zip->getNameIndex($i));
    if ($stream === false) {
        // tenta nome já normalizado
        $stream = $zip->getStream($name);
    }
    if ($stream === false) {
        $fail++;
        echo "Falha stream: {$name}\n";
        continue;
    }

    $out = fopen($target, 'wb');
    if ($out === false) {
        fclose($stream);
        $fail++;
        echo "Falha gravar: {$name}\n";
        continue;
    }
    stream_copy_to_stream($stream, $out);
    fclose($out);
    fclose($stream);
    $ok++;
}
$zip->close();

echo "\nExtraídos com sucesso: {$ok}\n";
echo "Falhas: {$fail}\n\n";

// 3) Checagens
$must = ['app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'storage', 'vendor', 'index.php', '.env', 'public/index.php', 'vendor/autoload.php'];
$missing = [];
foreach ($must as $m) {
    $p = $root . '/' . $m;
    if (! file_exists($p)) {
        $missing[] = $m;
    }
}

if ($missing) {
    echo "AINDA FALTANDO:\n- " . implode("\n- ", $missing) . "\n";
    echo "Algo deu errado na extração.\n";
} else {
    echo "OK: pastas do Laravel estão no lugar certo.\n";
    echo "Agora abra: https://cursosdecodar.com.br\n";
    echo "Depois APAGUE este arquivo deploy-extract.php e o ZIP.\n";
}

echo "\nPastas visíveis agora:\n";
foreach (scandir($root) ?: [] as $item) {
    if ($item === '.' || $item === '..') {
        continue;
    }
    if (is_dir($root . '/' . $item)) {
        echo "[DIR] {$item}\n";
    }
}

echo '</pre>';
