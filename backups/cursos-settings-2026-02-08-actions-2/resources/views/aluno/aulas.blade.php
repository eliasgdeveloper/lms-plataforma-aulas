@extends('layouts.aluno')

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_aulas/style.css') }}">
@endpush

<div class="page-header">
    <h1>Minhas Aulas</h1>
    <p>Acesse todas as suas aulas disponíveis</p>
</div>

<div class="aulas-container">
    <div class="aula-card">
        <h3>Introdução ao Laravel</h3>
        <p class="aula-desc">Aprenda os fundamentos do framework Laravel</p>
        <a href="#" class="btn btn-primary">Acessar Aula</a>
    </div>
    
    <div class="aula-card">
        <h3>PHP Avançado</h3>
        <p class="aula-desc">Conceitos avançados de programação PHP</p>
        <a href="#" class="btn btn-primary">Acessar Aula</a>
    </div>
    
    <div class="aula-card">
        <h3>Banco de Dados</h3>
        <p class="aula-desc">Design e otimização de bancos de dados</p>
        <a href="#" class="btn btn-primary">Acessar Aula</a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('pages/aluno_aulas/script.js') }}" defer></script>
@endpush
@endsection
