<?php
// Usage: php create_page.php page-name
// Creates:
// - resources/views/{page-name}/index.blade.php
// - public/pages/{page-name}/style.css
// - public/pages/{page-name}/script.js

if ($argc < 2) {
    echo "Usage: php create_page.php page-name\n";
    exit(1);
}

$name = $argv[1];
$viewsDir = __DIR__ . '/resources/views/' . $name;
$publicDir = __DIR__ . '/public/pages/' . $name;

if (!is_dir($viewsDir)) {
    mkdir($viewsDir, 0777, true);
}

if (!is_dir($publicDir)) {
    mkdir($publicDir, 0777, true);
}

$bladePath = $viewsDir . '/index.blade.php';
$cssPath = $publicDir . '/style.css';
$jsPath = $publicDir . '/script.js';

// Blade content - uses asset() to link CSS/JS
$bladeContent = <<<'BLADE'
@extends('layouts.student')

@section('content')
<!-- Page: %PAGE% -->
<link rel="stylesheet" href="{{ asset('pages/%PAGE%/style.css') }}">
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold">%TITLE%</h1>
    <p class="mt-4 text-gray-600">Conteúdo principal da página.</p>
</div>
<script src="{{ asset('pages/%PAGE%/script.js') }}" defer></script>
@endsection
BLADE;

$bladeContent = str_replace('%PAGE%', $name, $bladeContent);
$bladeContent = str_replace('%TITLE%', ucfirst(str_replace('-', ' ', $name)), $bladeContent);

file_put_contents($bladePath, $bladeContent);

// CSS starter
$cssContent = <<<CSS
/* Styles for page: $name */
body { background: #f8fafc; }
.page-container { padding: 1.5rem; }
CSS;
file_put_contents($cssPath, $cssContent);

// JS starter
$jsContent = <<<JS
// Script for page: $name
document.addEventListener('DOMContentLoaded', function(){
    console.log('Page $name loaded');
});
JS;
file_put_contents($jsPath, $jsContent);

echo "Created page scaffold for '$name'\n";
echo "- View: resources/views/$name/index.blade.php\n";
echo "- CSS: public/pages/$name/style.css\n";
echo "- JS: public/pages/$name/script.js\n";
echo "Next: add a route in routes/web.php, e.g.: Route::get('/$name', fn() => view('$name.index'))\n";
