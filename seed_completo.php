<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Curso;
use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\User;

// Buscar um professor
$professor = User::where('role', 'professor')->first();

if (!$professor) {
    echo "Erro: Nenhum professor encontrado!\n";
    exit(1);
}

// Criar um curso de teste se não existir
$curso = Curso::firstOrCreate(
    ['id' => 1],
    [
        'titulo' => 'Desenvolvimento Web com Laravel',
        'descricao' => 'Um curso completo sobre desenvolvimento web usando Laravel',
        'categoria' => 'Programação',
        'professor_id' => $professor->id,
    ]
);

echo "✓ Curso criado/encontrado: {$curso->titulo}\n";

// Criar uma aula de teste se não existir
$aula = Aula::firstOrCreate(
    ['id' => 1],
    [
        'curso_id' => $curso->id,
        'titulo' => 'Introdução ao Laravel',
        'descricao' => 'Aprenda os fundamentos do Laravel e comece a desenvolver aplicações web modernas',
        'data' => now(),
    ]
);

echo "✓ Aula criada/encontrada: {$aula->titulo}\n";

// Criar conteúdos de teste
$conteudosData = [
    [
        'titulo' => 'Introdução ao Laravel',
        'descricao' => 'Vídeo introdutório sobre os conceitos básicos do framework Laravel',
        'tipo' => 'video',
        'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
    ],
    [
        'titulo' => 'Documentação Oficial',
        'descricao' => 'Link para a documentação oficial do Laravel',
        'tipo' => 'link',
        'url' => 'https://laravel.com/docs',
    ],
    [
        'titulo' => 'Material de Estudo',
        'descricao' => 'PDF com os principais conceitos desenvolvidos na aula',
        'tipo' => 'pdf',
        'url' => 'https://example.com/material.pdf',
    ],
    [
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

foreach ($conteudosData as $conteudoData) {
    // Verificar se conteúdo já existe
    $conteudo = Conteudo::where('aula_id', $aula->id)
        ->where('titulo', $conteudoData['titulo'])
        ->first();
    
    if (!$conteudo) {
        $conteudo = $aula->conteudos()->create($conteudoData);
        echo "✓ Conteúdo criado: {$conteudo->titulo}\n";
    } else {
        echo "✓ Conteúdo já existe: {$conteudo->titulo}\n";
    }
}

echo "\nConteúdos da aula:\n";
$aula->conteudos()->get()->each(function ($c) {
    echo "- {$c->titulo} ({$c->tipo})\n";
});
