<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Conteudo;

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
}
