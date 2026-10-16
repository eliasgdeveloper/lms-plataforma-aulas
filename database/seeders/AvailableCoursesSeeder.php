<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Seeder;

class AvailableCoursesSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::query()
            ->whereIn('role', ['professor', 'admin'])
            ->orderByRaw("CASE role WHEN 'professor' THEN 0 ELSE 1 END")
            ->first()
            ?? User::query()->firstOrFail();

        $courses = [
            [
                'title' => 'JavaScript Moderno',
                'description' => 'DOM, ES2023, consumo de APIs e projetos reais do zero ao deploy.',
                'category' => 'Programacao',
                'hours' => 32,
                'lesson' => 'Fundamentos de JavaScript moderno',
                'content' => 'Comece com variáveis, tipos, funções e módulos ES. Em seguida, pratique manipulação do DOM, programação assíncrona com Promises e fetch, consumo de APIs e organização de um projeto moderno.',
            ],
            [
                'title' => 'Python para Dados',
                'description' => 'Automacao, analise de dados e bases para IA com estudos de caso.',
                'category' => 'Programacao',
                'hours' => 40,
                'lesson' => 'Primeiros passos com Python e dados',
                'content' => 'Conheça a sintaxe de Python, estruturas de dados e funções. A trilha avança para leitura, limpeza e análise exploratória de dados, com fundamentos que preparam para automação e inteligência artificial.',
            ],
            [
                'title' => 'HTML e CSS Profissional',
                'description' => 'Layouts responsivos, acessibilidade, performance e SEO on-page.',
                'category' => 'Web',
                'hours' => 20,
                'lesson' => 'Estrutura semântica e layouts responsivos',
                'content' => 'Construa páginas com HTML semântico e CSS organizado. Pratique Flexbox, Grid, responsividade, acessibilidade, otimização de carregamento e fundamentos de SEO on-page.',
            ],
            [
                'title' => 'PHP e Laravel Essencial',
                'description' => 'CRUD, autenticacao, boas praticas e APIs em Laravel.',
                'category' => 'Backend',
                'hours' => 36,
                'lesson' => 'Aplicações web com PHP e Laravel',
                'content' => 'Prepare o ambiente PHP e construa uma aplicação Laravel. Explore rotas, controllers, Blade, validação, autenticação, Eloquent, operações CRUD e criação de APIs seguindo boas práticas.',
            ],
            [
                'title' => 'SQL e Modelagem',
                'description' => 'Modelagem relacional, normalizacao e consultas otimizadas.',
                'category' => 'Banco de dados',
                'hours' => 18,
                'lesson' => 'Modelagem relacional e consultas SQL',
                'content' => 'Modele entidades e relacionamentos, aplique normalização e pratique SELECT, JOIN, agregações e subconsultas. Finalize analisando índices e estratégias para consultas mais eficientes.',
            ],
            [
                'title' => 'Sistemas Operacionais',
                'description' => 'Windows, Linux e fundamentos de infraestrutura para TI.',
                'category' => 'TI',
                'hours' => 16,
                'lesson' => 'Fundamentos de sistemas operacionais',
                'content' => 'Entenda processos, memória, arquivos, usuários e permissões. Compare tarefas do dia a dia em Windows e Linux, pratique comandos essenciais e conheça fundamentos de infraestrutura.',
            ],
            [
                'title' => 'Pacote Office na Pratica',
                'description' => 'Word, Excel e PowerPoint com foco em produtividade.',
                'category' => 'Produtividade',
                'hours' => 12,
                'lesson' => 'Produtividade com Word, Excel e PowerPoint',
                'content' => 'Organize documentos no Word, use fórmulas e tabelas para analisar dados no Excel e crie apresentações claras no PowerPoint. A aula reúne práticas úteis para tarefas reais de estudo e trabalho.',
            ],
            [
                'title' => 'Corel Draw para Design',
                'description' => 'Identidade visual, vetores e producao grafica profissional.',
                'category' => 'Design',
                'hours' => 14,
                'lesson' => 'Fundamentos de design vetorial',
                'content' => 'Conheça formas, curvas, cores, camadas e ferramentas de edição vetorial. Pratique a criação de uma identidade visual simples e prepare arquivos para diferentes formatos de produção gráfica.',
            ],
        ];

        foreach ($courses as $data) {
            $course = Curso::query()->firstOrCreate(
                ['titulo' => $data['title']],
                [
                    'descricao' => $data['description'],
                    'categoria' => $data['category'],
                    'duracao_horas_min' => $data['hours'],
                    'duracao_horas_max' => $data['hours'],
                    'nivel_trilha' => 'basico',
                    'professor_id' => $teacher->id,
                    'status' => 'ativo',
                ]
            );

            if ($course->aulas()->exists()) {
                continue;
            }

            $lesson = $course->aulas()->create([
                'titulo' => $data['lesson'],
                'descricao' => $data['description'],
                'ordem' => 1,
            ]);

            $lesson->conteudos()->create([
                'titulo' => $data['lesson'],
                'descricao' => $data['description'],
                'tipo' => 'texto',
                'url' => $data['content'],
                'ordem' => 1,
            ]);
        }

        User::query()
            ->where('role', 'aluno')
            ->get()
            ->each(fn (User $student) => $student->enrollInAvailableCourses());

        User::query()
            ->where('email', 'leandro@codar.local')
            ->first()
            ?->enrollInAvailableCourses();
    }
}
