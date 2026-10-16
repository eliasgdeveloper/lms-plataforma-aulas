<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aula;
use App\Models\Conteudo;

class AulaSeeder extends Seeder
{
    public function run(): void
    {
        // Cria uma aula fake
        $aula = Aula::create([
            'curso_id' => 1, // supondo que já exista um curso com ID 1
            'titulo' => 'Tipos de Referência de Célula',
            'descricao' => 'Aula sobre referências absolutas, relativas e mistas em planilhas.',
            'data' => now(),
        ]);

        // Cria alguns conteúdos para essa aula
        Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Vídeo: Introdução às referências',
            'descricao' => 'Explicação sobre referências absolutas e relativas.',
            'tipo' => 'video',
            'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ]);

        Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'PDF: Apostila de referências',
            'descricao' => 'Material complementar com exemplos.',
            'tipo' => 'pdf',
            'url' => 'https://exemplo.com/apostila_referencias.pdf',
        ]);

        Conteudo::create([
            'aula_id' => $aula->id,
            'titulo' => 'Link: Exercício online',
            'descricao' => 'Pratique referências em planilhas.',
            'tipo' => 'link',
            'url' => 'https://exemplo.com/exercicio-referencias',
        ]);
    }
}
