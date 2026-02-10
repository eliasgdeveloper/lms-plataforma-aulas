<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakePage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:page {name : The page name (folder will be created under resources/views and public/pages)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold a simple page in resources/views/<name>/ and public/pages/<name>/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = trim($this->argument('name'));

        if ($name === '') {
            $this->error('Please provide a valid page name.');
            return 1;
        }

        // New structure: resources/views/pages/{name}/
        $viewDir = base_path('resources/views/pages/' . $name);
        $publicDir = public_path('pages/' . $name);

        if (is_dir($viewDir) || is_dir($publicDir)) {
            $this->error("A page named '{$name}' already exists.");
            return 1;
        }

        if (!mkdir($viewDir, 0755, true) || !mkdir($publicDir, 0755, true)) {
            $this->error('Failed to create directories.');
            return 1;
        }

        $viewPath = $viewDir . '/index.blade.php';
        $viewCssPath = $viewDir . '/style.css';
        $viewJsPath = $viewDir . '/script.js';
        $cssPath = $publicDir . '/style.css';
        $jsPath = $publicDir . '/script.js';

        // Create Blade view
        $viewContent = "@extends('layouts.page')\n\n@section('title', '" . ucfirst($name) . "')\n\n@push('styles')\n<link rel=\"stylesheet\" href=\"{{ asset('pages/{$name}/style.css') }}\">\n@endpush\n\n@section('content')\n<div class=\"page-container\">\n    <h1>" . ucfirst($name) . "</h1>\n    <p>Conteúdo inicial da página {$name}.</p>\n</div>\n@endsection\n\n@push('scripts')\n<script src=\"{{ asset('pages/{$name}/script.js') }}\" defer></script>\n@endpush";

        $cssContent = "/* Styles for page {$name} */\n\n:root {\n    --primary: #003d82;\n    --secondary: #0051b8;\n    --text-dark: #1b1b18;\n    --text-light: #6b7280;\n    --bg-light: #f3f4f6;\n    --bg-white: #ffffff;\n    --border: #e5e7eb;\n}\n\n@media (prefers-color-scheme: dark) {\n    :root {\n        --text-dark: #f5f5f1;\n        --text-light: #d1d5db;\n        --bg-light: #1f2937;\n        --bg-white: #111827;\n        --border: #374151;\n    }\n}\n\n.page-container {\n    max-width: 1200px;\n    margin: 0 auto;\n    padding: 2rem;\n}\n\n.page-container h1 {\n    font-size: 2rem;\n    font-weight: 700;\n    color: var(--primary);\n    margin-bottom: 1rem;\n}\n\n.page-container p {\n    font-size: 1rem;\n    color: var(--text-light);\n}\n\n@media (max-width: 640px) {\n    .page-container {\n        padding: 1rem;\n    }\n    .page-container h1 {\n        font-size: 1.5rem;\n    }\n}";

        $jsContent = "// Script for page {$name}\n\ndocument.addEventListener('DOMContentLoaded', function() {\n    console.log('Page {$name} loaded');\n});";

        // Write files to both locations
        file_put_contents($viewPath, $viewContent);
        file_put_contents($viewCssPath, $cssContent);
        file_put_contents($viewJsPath, $jsContent);
        file_put_contents($cssPath, $cssContent);
        file_put_contents($jsPath, $jsContent);

        $this->info("✓ Created view: resources/views/pages/{$name}/index.blade.php");
        $this->info("✓ Created view CSS: resources/views/pages/{$name}/style.css");
        $this->info("✓ Created view JS: resources/views/pages/{$name}/script.js");
        $this->info("✓ Created public CSS: public/pages/{$name}/style.css");
        $this->info("✓ Created public JS: public/pages/{$name}/script.js");
        $this->info("\n✅ Page '{$name}' created successfully!");
        $this->info("Usage: return view('pages.{$name}.index');");

        return 0;
    }
}
