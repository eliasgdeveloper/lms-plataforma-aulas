<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Conteudo;
use App\Models\ConteudoProgresso;
use App\Models\Enrollment;

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
        if (! $user || $user->role !== 'aluno') {
            abort(403);
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

        $progress = (int) $data['progress'];

        $completed = $progress >= 90;

        ConteudoProgresso::updateOrCreate(
            [
                'user_id' => $user->id,
                'conteudo_id' => $conteudo->id,
            ],
            [
                'progresso' => $progress,
                'concluido' => $completed,
            ]
        );

        return response()->json([
            'progress' => $progress,
            'completed' => $completed,
        ]);
    }
}
