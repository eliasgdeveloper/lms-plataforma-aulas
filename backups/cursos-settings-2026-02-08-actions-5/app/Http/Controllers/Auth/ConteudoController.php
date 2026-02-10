<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Conteudo;
use Illuminate\Http\Request;

class ConteudoController extends Controller
{
    // Lista os conteúdos de uma aula
    public function index($aulaId)
    {
        $aula = Aula::with('conteudos')->findOrFail($aulaId);

        return view('aluno.conteudos', [
            'aula' => $aula,
            'conteudoSelecionado' => $aula->conteudos->first() // mostra o primeiro por padrão
        ]);
    }

    // Exibe um conteúdo específico
    public function show($conteudoId)
    {
        $conteudo = Conteudo::findOrFail($conteudoId);
        $aula = $conteudo->aula;

        return view('aluno.conteudos', [
            'aula' => $aula,
            'conteudoSelecionado' => $conteudo
        ]);
    }
}
