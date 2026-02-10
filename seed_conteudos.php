<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Conteudo;

// Verificar se já existe conteúdo na aula 1
$conteudoExistente = Conteudo::where('aula_id', 1)->first();

if (!$conteudoExistente) {
    // Criar conteúdos de teste
    $conteudos = [
        [
            'aula_id' => 1,
            'titulo' => 'Introdução ao Laravel',
            'descricao' => 'Vídeo introdutório sobre os conceitos básicos do framework Laravel',
            'tipo' => 'video',
            'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ],
        [
            'aula_id' => 1,
            'titulo' => 'Documentação Oficial',
            'descricao' => 'Link para a documentação oficial do Laravel',
            'tipo' => 'link',
            'url' => 'https://laravel.com/docs',
        ],
        [
            'aula_id' => 1,
            'titulo' => 'Material de Estudo',
            'descricao' => 'PDF com os principais conceitos desenvolvidos na aula',
            'tipo' => 'pdf',
            'url' => 'https://example.com/material.pdf',
        ],
        [
            'aula_id' => 1,
            'titulo' => 'Notas da Aula',
            'descricao' => 'Notas e resumo dos principais pontos discutidos',
            'tipo' => 'texto',
            'url' => 'Nesta aula aprendemos sobre:
- Estrutura do Laravel
- Routes e Controllers
- Models e Migrations
- Blade Templates',
        ],
    ];

    foreach ($conteudos as $conteudo) {
        Conteudo::create($conteudo);
        echo "✓ Conteúdo criado: {$conteudo['titulo']}\n";
    }
} else {
    echo "✓ Conteúdos já existem para a aula 1\n";
}

echo "\nConteúdos da aula 1:\n";
$conteudos = Conteudo::where('aula_id', 1)->get();
foreach ($conteudos as $c) {
    echo "- {$c->titulo} ({$c->tipo})\n";
}
