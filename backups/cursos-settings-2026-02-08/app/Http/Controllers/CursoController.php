<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function adminCreate()
    {
        return $this->renderCreate('admin');
    }

    public function professorCreate()
    {
        return $this->renderCreate('professor');
    }

    public function adminStore(Request $request)
    {
        return $this->storeCourse($request, 'admin.cursos');
    }

    public function professorStore(Request $request)
    {
        return $this->storeCourse($request, 'professor.cursos');
    }

    public function adminShow(Curso $curso)
    {
        return $this->renderSettings($curso, 'admin');
    }

    public function professorShow(Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->renderSettings($curso, 'professor');
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

    private function renderCreate(string $role)
    {
        $data = $this->buildCreateData();

        return view("pages.{$role}_cursos.create", $data);
    }

    private function renderSettings(Curso $curso, string $role)
    {
        return view("pages.{$role}_cursos.show", $this->buildSettingsData($curso, $role));
    }

    private function buildCourses(): array
    {
        $dbCourses = Curso::with('professor')->latest()->get();

        if ($dbCourses->isNotEmpty()) {
            return $dbCourses->map(function (Curso $curso) {
                return [
                    'id' => $curso->id,
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
                'id' => null,
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
                'id' => null,
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
                'id' => null,
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
                'id' => null,
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
                'id' => null,
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
                'id' => null,
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
                'id' => null,
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
                'id' => null,
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

    private function buildCreateData(): array
    {
        // Fetch categories from DB when available, fallback otherwise.
        $dbCategories = DB::table('categorias')
            ->select('id', 'nome')
            ->orderBy('nome')
            ->get();

        $categories = $dbCategories->isNotEmpty()
            ? $dbCategories->pluck('nome')->all()
            : [
                'Programacao',
                'Web',
                'Banco de dados',
                'Design',
                'Produtividade',
                'TI',
            ];

        // New course: list all students as available for enrollment.
        $students = User::query()
            ->where('role', 'aluno')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return [
            'categories' => $categories,
            'students' => $students,
        ];
    }

    private function storeCourse(Request $request, string $redirectRoute)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:120',
            'descricao' => 'required|string|max:2000',
            'categoria' => 'nullable|string|max:60',
            'alunos' => 'nullable|array',
            'alunos.*' => 'integer|exists:users,id',
        ]);

        // Map minimal fields supported by the current cursos table.
        $curso = Curso::create([
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'categoria' => $validated['categoria'] ?? null,
            'professor_id' => auth()->id(),
        ]);

        // Enroll selected students for the new course.
        if (!empty($validated['alunos'])) {
            $now = now()->toDateString();
            foreach ($validated['alunos'] as $studentId) {
                DB::table('matriculas')->insert([
                    'aluno_id' => $studentId,
                    'curso_id' => $curso->id,
                    'data_matricula' => $now,
                    'status' => 'ativo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()
            ->route($redirectRoute)
            ->with('success', "Curso '{$curso->titulo}' criado com sucesso.");
    }

    private function buildSettingsData(Curso $curso, string $role): array
    {
        $modules = [
            [
                'title' => 'Geral',
                'items' => [
                    ['label' => 'Instrucoes importantes', 'type' => 'alert'],
                    ['label' => 'Avisos', 'type' => 'note'],
                    ['label' => 'Presenca e atividade', 'type' => 'note'],
                    ['label' => 'Video do instrutor', 'type' => 'alert'],
                ],
            ],
            [
                'title' => 'Primeiro modulo',
                'items' => [
                    ['label' => 'Introducao', 'type' => 'lesson'],
                    ['label' => 'Quiz de boas-vindas', 'type' => 'quiz'],
                ],
            ],
            [
                'title' => 'Segundo modulo',
                'items' => [
                    ['label' => 'Conteudo pratico', 'type' => 'lesson'],
                    ['label' => 'Avaliacao', 'type' => 'quiz'],
                ],
            ],
        ];

        $quickLinks = $role === 'admin'
            ? [
                ['label' => 'Pagina inicial', 'href' => url('/')],
                ['label' => 'Painel', 'href' => route('admin.dashboard')],
                ['label' => 'Meus cursos', 'href' => route('admin.cursos')],
                ['label' => 'Administracao do site', 'href' => route('admin.configuracoes')],
                ['label' => 'Voltar', 'href' => route('admin.cursos')],
            ]
            : [
                ['label' => 'Pagina inicial', 'href' => url('/')],
                ['label' => 'Painel', 'href' => route('professor.dashboard')],
                ['label' => 'Meus cursos', 'href' => route('professor.cursos')],
                ['label' => 'Administracao do site', 'href' => route('professor.dashboard')],
                ['label' => 'Voltar', 'href' => route('professor.cursos')],
            ];

        return [
            'curso' => $curso,
            'modules' => $modules,
            'quickLinks' => $quickLinks,
        ];
    }
}
