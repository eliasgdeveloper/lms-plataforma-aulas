<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoProgresso;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function adminReport()
    {
        return view('pages.admin_cursos.report', $this->buildCourseStats());
    }

    public function adminOverview()
    {
        return view('pages.admin_cursos.overview', $this->buildCourseStats());
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

    public function adminUpdate(Request $request, Curso $curso)
    {
        return $this->updateCourse($request, $curso, 'admin');
    }

    public function professorUpdate(Request $request, Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->updateCourse($request, $curso, 'professor');
    }

    public function adminShow(Curso $curso)
    {
        return $this->renderSettings($curso, 'admin');
    }

    public function adminEdit(Curso $curso)
    {
        return $this->renderEdit($curso, 'admin');
    }

    public function professorShow(Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->renderSettings($curso, 'professor');
    }

    public function professorEdit(Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->renderEdit($curso, 'professor');
    }

    public function adminAction(Request $request, Curso $curso, string $acao)
    {
        return $this->handleCourseAction($request, $curso, $acao, 'admin');
    }

    public function professorAction(Request $request, Curso $curso, string $acao)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->handleCourseAction($request, $curso, $acao, 'professor');
    }

    public function adminStoreConteudo(Request $request, Curso $curso)
    {
        return $this->storeConteudo($request, $curso, 'admin');
    }

    public function professorStoreConteudo(Request $request, Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->storeConteudo($request, $curso, 'professor');
    }

    public function adminStoreAula(Request $request, Curso $curso)
    {
        return $this->storeAula($request, $curso, 'admin');
    }

    public function professorStoreAula(Request $request, Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->storeAula($request, $curso, 'professor');
    }

    public function adminUpdateConteudo(Request $request, Curso $curso, Conteudo $conteudo)
    {
        return $this->updateConteudo($request, $curso, $conteudo, 'admin');
    }

    public function professorUpdateConteudo(Request $request, Curso $curso, Conteudo $conteudo)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->updateConteudo($request, $curso, $conteudo, 'professor');
    }

    public function adminUpdateAula(Request $request, Curso $curso, Aula $aula)
    {
        return $this->updateAula($request, $curso, $aula, 'admin');
    }

    public function professorUpdateAula(Request $request, Curso $curso, Aula $aula)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->updateAula($request, $curso, $aula, 'professor');
    }

    public function adminToggleConteudo(Curso $curso, Conteudo $conteudo)
    {
        return $this->toggleConteudo($curso, $conteudo, 'admin');
    }

    public function professorToggleConteudo(Curso $curso, Conteudo $conteudo)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->toggleConteudo($curso, $conteudo, 'professor');
    }

    public function adminToggleAula(Curso $curso, Aula $aula)
    {
        return $this->toggleAula($curso, $aula, 'admin');
    }

    public function professorToggleAula(Curso $curso, Aula $aula)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->toggleAula($curso, $aula, 'professor');
    }

    public function adminDestroyConteudo(Curso $curso, Conteudo $conteudo)
    {
        return $this->destroyConteudo($curso, $conteudo, 'admin');
    }

    public function professorDestroyConteudo(Curso $curso, Conteudo $conteudo)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->destroyConteudo($curso, $conteudo, 'professor');
    }

    public function adminDestroyAula(Curso $curso, Aula $aula)
    {
        return $this->destroyAula($curso, $aula, 'admin');
    }

    public function professorDestroyAula(Curso $curso, Aula $aula)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->destroyAula($curso, $aula, 'professor');
    }

    public function adminPreview(Curso $curso)
    {
        return $this->previewCurso($curso);
    }

    public function professorPreview(Curso $curso)
    {
        if ($curso->professor_id !== auth()->id()) {
            abort(403);
        }

        return $this->previewCurso($curso);
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

    private function buildCourseStats(): array
    {
        return [
            'total' => Curso::count(),
            'ativos' => Curso::where('status', 'ativo')->count(),
            'ocultos' => Curso::where('status', 'oculto')->count(),
            'agendados' => Curso::where('status', 'agendado')->count(),
            'rascunhos' => Curso::where('status', 'rascunho')->count(),
        ];
    }

    private function renderCreate(string $role)
    {
        $data = $this->buildCreateData();

        return view("pages.{$role}_cursos.create", $data);
    }

    private function renderEdit(Curso $curso, string $role)
    {
        $data = $this->buildCreateData();
        $data['curso'] = $curso;

        return view("pages.{$role}_cursos.edit", $data);
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
                    'status' => $curso->status ?? 'ativo',
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
        $validated = $this->validateCourse($request);

        // Map minimal fields supported by the current cursos table.
        $curso = Curso::create([
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'categoria' => $validated['categoria'] ?? null,
            'professor_id' => auth()->id(),
            'status' => $validated['status'] ?? 'ativo',
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

    private function updateCourse(Request $request, Curso $curso, string $role)
    {
        $validated = $this->validateCourse($request, true);

        $curso->update([
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'],
            'categoria' => $validated['categoria'] ?? null,
            'status' => $validated['status'] ?? $curso->status,
        ]);

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', "Curso '{$curso->titulo}' atualizado com sucesso.");
    }

    private function validateCourse(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:120',
            'descricao' => 'required|string|max:2000',
            'categoria' => 'nullable|string|max:60',
            'status' => 'nullable|string|in:rascunho,ativo,oculto,agendado',
            'alunos' => $isUpdate ? 'nullable' : 'nullable|array',
            'alunos.*' => $isUpdate ? 'nullable' : 'integer|exists:users,id',
        ]);
    }

    private function buildSettingsData(Curso $curso, string $role): array
    {
        $aulas = Aula::query()
            ->where('curso_id', $curso->id)
            ->with(['conteudos' => function ($query) {
                $query->orderBy('ordem')->orderBy('id');
            }])
            ->orderBy('ordem')
            ->orderBy('id')
            ->get();

        $conteudoIds = $aulas->flatMap(function (Aula $aula) {
            return $aula->conteudos->pluck('id');
        })->unique();

        $progressMap = $conteudoIds->isNotEmpty()
            ? ConteudoProgresso::query()
                ->where('user_id', auth()->id())
                ->whereIn('conteudo_id', $conteudoIds)
                ->pluck('progresso', 'conteudo_id')
            : collect();

        $modules = $aulas->map(function (Aula $aula) use ($progressMap) {
            $items = $aula->conteudos->map(function (Conteudo $conteudo) use ($progressMap) {
                $progress = (int) ($progressMap[$conteudo->id] ?? 0);

                return [
                    'id' => $conteudo->id,
                    'label' => $conteudo->titulo,
                    'type' => $conteudo->tipo,
                    'descricao' => $conteudo->descricao,
                    'is_hidden' => (bool) $conteudo->is_hidden,
                    'progress' => $progress,
                    'completed' => $progress >= 100,
                    'url' => $conteudo->url,
                    'arquivo' => $conteudo->arquivo,
                    'ordem' => $conteudo->ordem,
                ];
            });

            $moduleProgress = $items->isNotEmpty()
                ? (int) round($items->avg('progress'))
                : 0;

            return [
                'id' => $aula->id,
                'title' => $aula->titulo,
                'descricao' => $aula->descricao,
                'ordem' => $aula->ordem,
                'is_hidden' => (bool) ($aula->is_hidden ?? false),
                'progress' => $moduleProgress,
                'items' => $items->all(),
            ];
        })->all();

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
            'role' => $role,
            'aulas' => $aulas,
            'aulasStoreRoute' => route("{$role}.cursos.aulas.store", $curso),
            'conteudosStoreRoute' => route("{$role}.cursos.conteudos.store", $curso),
            'conteudosPreviewRoute' => route("{$role}.cursos.preview", $curso),
        ];
    }

    private function storeAula(Request $request, Curso $curso, string $role)
    {
        $data = $this->validateAula($request);

        Aula::create([
            'curso_id' => $curso->id,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'ordem' => $data['ordem'] ?? 0,
        ]);

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Modulo criado com sucesso.');
    }

    private function updateAula(Request $request, Curso $curso, Aula $aula, string $role)
    {
        $data = $this->validateAula($request, true);

        $aula->update([
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? $aula->descricao,
            'ordem' => $data['ordem'] ?? $aula->ordem,
        ]);

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Modulo atualizado com sucesso.');
    }

    private function toggleAula(Curso $curso, Aula $aula, string $role)
    {
        $aula->update(['is_hidden' => ! $aula->is_hidden]);

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Visibilidade do modulo atualizada.');
    }

    private function destroyAula(Curso $curso, Aula $aula, string $role)
    {
        $aula->delete();

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Modulo removido com sucesso.');
    }

    private function storeConteudo(Request $request, Curso $curso, string $role)
    {
        $data = $this->validateConteudo($request);
        $aula = $this->resolveAula($curso, $data);

        $conteudo = new Conteudo([
            'aula_id' => $aula->id,
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'tipo' => $data['tipo'],
            'url' => $data['url'] ?? null,
            'ordem' => $data['ordem'] ?? 0,
        ]);

        if ($request->hasFile('arquivo')) {
            $conteudo->arquivo = $request->file('arquivo')->store('conteudos', 'public');
        }

        $conteudo->save();

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Conteudo adicionado com sucesso.');
    }

    private function updateConteudo(Request $request, Curso $curso, Conteudo $conteudo, string $role)
    {
        $data = $this->validateConteudo($request, true);

        $conteudo->fill([
            'titulo' => $data['titulo'],
            'descricao' => $data['descricao'] ?? null,
            'tipo' => $data['tipo'],
            'url' => $data['url'] ?? null,
            'ordem' => $data['ordem'] ?? $conteudo->ordem,
        ]);

        if ($request->hasFile('arquivo')) {
            if ($conteudo->arquivo) {
                Storage::disk('public')->delete($conteudo->arquivo);
            }
            $conteudo->arquivo = $request->file('arquivo')->store('conteudos', 'public');
        }

        $conteudo->save();

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Conteudo atualizado com sucesso.');
    }

    private function toggleConteudo(Curso $curso, Conteudo $conteudo, string $role)
    {
        $conteudo->update(['is_hidden' => ! $conteudo->is_hidden]);

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Visibilidade do conteudo atualizada.');
    }

    private function destroyConteudo(Curso $curso, Conteudo $conteudo, string $role)
    {
        if ($conteudo->arquivo) {
            Storage::disk('public')->delete($conteudo->arquivo);
        }

        $conteudo->delete();

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', 'Conteudo removido com sucesso.');
    }

    private function validateConteudo(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'aula_id' => $isUpdate ? 'nullable|integer|exists:aulas,id' : 'nullable|integer|exists:aulas,id',
            'aula_titulo' => $isUpdate ? 'nullable|string|max:120' : 'required_without:aula_id|string|max:120',
            'titulo' => 'required|string|max:150',
            'descricao' => 'nullable|string|max:2000',
            'tipo' => 'required|string|in:video,pdf,link,texto,arquivo,word,excel,quiz,prova,tarefa',
            'url' => 'nullable|url|max:500',
            'ordem' => 'nullable|integer|min:0',
            'arquivo' => 'nullable|file|max:51200',
        ]);
    }

    private function validateAula(Request $request, bool $isUpdate = false): array
    {
        return $request->validate([
            'titulo' => 'required|string|max:120',
            'descricao' => 'nullable|string|max:2000',
            'ordem' => 'nullable|integer|min:0',
        ]);
    }

    private function resolveAula(Curso $curso, array $data): Aula
    {
        if (!empty($data['aula_id'])) {
            return Aula::where('curso_id', $curso->id)->findOrFail($data['aula_id']);
        }

        return Aula::create([
            'curso_id' => $curso->id,
            'titulo' => $data['aula_titulo'],
            'descricao' => $data['descricao'] ?? null,
            'ordem' => 0,
        ]);
    }

    private function previewCurso(Curso $curso)
    {
        $aula = Aula::where('curso_id', $curso->id)->orderBy('ordem')->orderBy('id')->first();

        if (!$aula) {
            return view('pages.aluno_conteudos.index', [
                'aula' => null,
            ]);
        }

        $aula->load('conteudos');

        return view('pages.aluno_conteudos.index', [
            'aula' => $aula,
        ]);
    }

    private function handleCourseAction(Request $request, Curso $curso, string $acao, string $role)
    {
        $allowed = ['ocultar', 'iniciar', 'agendar', 'excluir'];
        if (!in_array($acao, $allowed, true)) {
            abort(404);
        }

        if ($acao === 'agendar') {
            $request->validate([
                'agendado_em' => 'required|date',
            ]);
        }

        if ($acao === 'excluir') {
            $curso->delete();

            return redirect()
                ->route("{$role}.cursos")
                ->with('success', "Curso '{$curso->titulo}' excluido com sucesso.");
        }

        switch ($acao) {
            case 'ocultar':
                $curso->update([
                    'status' => 'oculto',
                    'agendado_em' => null,
                ]);
                $message = "Curso '{$curso->titulo}' ocultado.";
                break;
            case 'iniciar':
                $curso->update([
                    'status' => 'ativo',
                    'agendado_em' => null,
                ]);
                $message = "Curso '{$curso->titulo}' iniciado.";
                break;
            case 'agendar':
                $curso->update([
                    'status' => 'agendado',
                    'agendado_em' => $request->input('agendado_em'),
                ]);
                $message = "Curso '{$curso->titulo}' agendado.";
                break;
            default:
                $message = "Acao aplicada ao curso '{$curso->titulo}'.";
                break;
        }

        return redirect()
            ->route("{$role}.cursos.show", $curso)
            ->with('success', $message);
    }
}
