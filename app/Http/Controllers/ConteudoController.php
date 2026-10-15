<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoProgresso;
use App\Models\Enrollment;
use App\Support\ViewAs;

class ConteudoController extends Controller
{
    /**
     * Display a listing of the contents of a specific class.
     */
    public function index($aula)
    {
        $aula = Aula::findOrFail($aula);
        $conteudos = $aula->conteudos()->get();

        return view('aluno.conteudos.index', [
            'aula' => $aula,
            'conteudos' => $conteudos,
        ]);
    }

    /**
     * Display the specified content.
     */
    public function show(Conteudo $conteudo)
    {
        // If this is an AJAX request, return only the partial HTML
        if (request()->ajax()) {
            return view('aluno.conteudos._partial', [
                'conteudo' => $conteudo,
                'aula' => $conteudo->aula,
            ]);
        }

        return view('aluno.conteudos.show', [
            'conteudo' => $conteudo,
            'aula' => $conteudo->aula,
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
