<?php

namespace App\Http\Controllers;

use App\Models\Curso;

class CursoController extends Controller
{
    public function alunoIndex()
    {
        return $this->renderCursos('aluno');
    }

    public function professorIndex()
    {
        return $this->renderCursos('professor');
    }

    public function adminIndex()
    {
        return $this->renderCursos('admin');
    }

    private function renderCursos(string $role)
    {
        $courses = $this->buildCourses();

        return view("pages.{$role}_cursos.index", [
            'courses' => $courses,
            'isAdmin' => $role === 'admin',
            'isProfessor' => $role === 'professor',
        ]);
    }

    private function buildCourses(): array
    {
        $dbCourses = Curso::with('professor')->latest()->get();

        if ($dbCourses->isNotEmpty()) {
            return $dbCourses->map(function (Curso $curso) {
                return [
                    'title' => $curso->titulo,
                    'description' => $curso->descricao ?? 'Curso atualizado com conteudo pratico e trilha guiada.',
                    'category' => $curso->categoria ?? 'Tecnologia',
                    'level' => 'Intermediario',
                    'duration' => '24h',
                    'tags' => [$curso->categoria ?? 'TI', 'Certificado', 'Projeto final'],
                    'teacher' => $curso->professor?->name ?? 'Equipe LMS',
                    'status' => 'ativo',
                    'preview_url' => '#',
                    'syllabus_url' => '#',
                    'enroll_url' => '#',
                ];
            })->all();
        }

        // Fallback para ambiente sem cursos cadastrados.
        return [
            [
                'title' => 'JavaScript Moderno',
                'description' => 'DOM, ES2023, consumo de APIs e projetos reais do zero ao deploy.',
                'category' => 'Programacao',
                'level' => 'Intermediario',
                'duration' => '32h',
                'tags' => ['Front-end', 'Projetos', 'APIs'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'Python para Dados',
                'description' => 'Automacao, analise de dados e bases para IA com estudos de caso.',
                'category' => 'Programacao',
                'level' => 'Basico a avancado',
                'duration' => '40h',
                'tags' => ['Dados', 'IA', 'Automacao'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'HTML e CSS Profissional',
                'description' => 'Layouts responsivos, acessibilidade, performance e SEO on-page.',
                'category' => 'Web',
                'level' => 'Basico a intermediario',
                'duration' => '20h',
                'tags' => ['Responsivo', 'SEO', 'UX'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'PHP e Laravel Essencial',
                'description' => 'CRUD, autenticacao, boas praticas e APIs em Laravel.',
                'category' => 'Backend',
                'level' => 'Intermediario',
                'duration' => '36h',
                'tags' => ['Laravel', 'APIs', 'Seguranca'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'SQL e Modelagem',
                'description' => 'Modelagem relacional, normalizacao e consultas otimizadas.',
                'category' => 'Banco de dados',
                'level' => 'Intermediario',
                'duration' => '18h',
                'tags' => ['SQL', 'Modelagem', 'Performance'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'Sistemas Operacionais',
                'description' => 'Windows, Linux e fundamentos de infraestrutura para TI.',
                'category' => 'TI',
                'level' => 'Basico',
                'duration' => '16h',
                'tags' => ['Windows', 'Linux', 'Fundamentos'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'Pacote Office na Pratica',
                'description' => 'Word, Excel e PowerPoint com foco em produtividade.',
                'category' => 'Produtividade',
                'level' => 'Basico',
                'duration' => '12h',
                'tags' => ['Excel', 'Word', 'PowerPoint'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
            [
                'title' => 'Corel Draw para Design',
                'description' => 'Identidade visual, vetores e producao grafica profissional.',
                'category' => 'Design',
                'level' => 'Basico a intermediario',
                'duration' => '14h',
                'tags' => ['Design', 'Vetor', 'Branding'],
                'teacher' => 'Equipe LMS',
                'status' => 'ativo',
                'preview_url' => '#',
                'syllabus_url' => '#',
                'enroll_url' => '#',
            ],
        ];
    }
}
