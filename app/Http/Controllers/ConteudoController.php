<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoProgresso;
use App\Models\Enrollment;
use App\Support\PhpTypingExercise;
use App\Support\ViewAs;
use Illuminate\Support\Str;

class ConteudoController extends Controller
{
    /**
     * Display a listing of the contents of a specific class.
     */
    public function index($aula)
    {
        $aula = Aula::findOrFail($aula);
        $conteudos = $aula->conteudos()->get();
        $isPhpCourse = Str::contains(Str::lower(Str::ascii($aula->curso?->titulo ?? '')), 'php');

        return view('aluno.conteudos.index', [
            'aula' => $aula,
            'conteudos' => $conteudos,
            'isPhpCourse' => $isPhpCourse,
        ]);
    }

    /**
     * Display the specified content.
     */
    public function show(Conteudo $conteudo)
    {
        $conteudo->loadMissing('aula.curso');
        $aula = $conteudo->aula;

        // If this is an AJAX request, return only the partial HTML
        if (request()->ajax()) {
            return view('aluno.conteudos._partial', [
                'conteudo' => $conteudo,
                'aula' => $aula,
            ]);
        }

        return view('aluno.conteudos.show', [
            'conteudo' => $conteudo,
            'aula' => $aula,
        ]);
    }

    public function typing(Conteudo $conteudo)
    {
        $conteudo->loadMissing('aula.curso');
        $aula = $conteudo->aula;
        $typingExercise = $aula
            ? PhpTypingExercise::forSummary(
                $aula->curso?->titulo,
                $aula->titulo,
                $conteudo->titulo,
                $conteudo->tipo
            )
            : null;

        if (! $typingExercise) {
            abort(404);
        }

        if (
            ! ViewAs::isPreview()
            && ! Enrollment::query()
                ->where('aluno_id', auth()->id())
                ->where('curso_id', $aula->curso_id)
                ->whereIn('status', ['ativo', 'concluido'])
                ->exists()
        ) {
            abort(403);
        }

        $lessons = Aula::query()
            ->where('curso_id', $aula->curso_id)
            ->where(fn ($query) => $query->whereNull('is_hidden')->orWhere('is_hidden', false))
            ->with(['conteudos' => function ($query) {
                $query
                    ->where(fn ($inner) => $inner->whereNull('is_hidden')->orWhere('is_hidden', false))
                    ->orderBy('ordem')
                    ->orderBy('id');
            }])
            ->orderBy('ordem')
            ->orderBy('id')
            ->get();
        $modules = $lessons->map(function (Aula $lesson) use ($aula) {
            $summaryId = $lesson->conteudos->first(function (Conteudo $content) use ($aula, $lesson) {
                return PhpTypingExercise::forSummary(
                    $aula->curso?->titulo,
                    $lesson->titulo,
                    $content->titulo,
                    $content->tipo
                ) !== null;
            })?->id;

            return [
                'id' => $lesson->id,
                'title' => $lesson->titulo,
                'items' => $lesson->conteudos->map(function (Conteudo $content) use ($summaryId) {
                    $item = [
                        'id' => $content->id,
                        'title' => $content->titulo,
                        'type' => $content->tipo,
                        'url' => route('conteudos.show', $content),
                    ];

                    if (
                        $summaryId !== null
                        && $content->tipo === 'pdf'
                        && Str::startsWith(Str::lower($content->titulo), 'guia')
                    ) {
                        $item['typing_url'] = route('conteudos.typing', $summaryId);
                        $item['typing_content_id'] = $summaryId;
                    }

                    return $item;
                })->all(),
            ];
        })->all();

        return view('aluno.conteudos.typing', [
            'conteudo' => $conteudo,
            'aula' => $aula,
            'curso' => $aula->curso,
            'typingExercise' => $typingExercise,
            'modules' => $modules,
        ]);
    }

    public function updateProgress(Request $request, Conteudo $conteudo)
    {
        $user = auth()->user();
        if (! $user || ($user->role !== 'aluno' && ! ViewAs::isPreview())) {
            abort(403);
        }

        if (ViewAs::isPreview()) {
            return response()->json([
                'progress' => (int) $request->input('progress', 0),
                'completed' => false,
                'preview' => true,
            ]);
        }

        $cursoId = $conteudo->aula?->curso_id;
        if (! $cursoId) {
            abort(404);
        }

        $isEnrolled = Enrollment::query()
            ->where('aluno_id', $user->id)
            ->where('curso_id', $cursoId)
            ->exists();

        if (! $isEnrolled) {
            abort(403);
        }

        $data = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $incoming = (int) $data['progress'];

        $existing = ConteudoProgresso::query()
            ->where('user_id', $user->id)
            ->where('conteudo_id', $conteudo->id)
            ->first();

        // Quizzes/provas: vale a maior nota (não regride).
        $progress = max($incoming, (int) ($existing->progresso ?? 0));
        $completed = $progress >= 90;

        ConteudoProgresso::updateOrCreate(
            [
                'user_id' => $user->id,
                'conteudo_id' => $conteudo->id,
            ],
            [
                'progresso' => $progress,
                'concluido' => $completed || (bool) ($existing->concluido ?? false),
            ]
        );

        return response()->json([
            'progress' => $progress,
            'completed' => $completed,
            'best' => true,
        ]);
    }
}
