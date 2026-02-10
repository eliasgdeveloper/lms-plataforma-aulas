@extends('layouts.aluno')

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_materiais/style.css') }}">
@endpush

<div class="page-header">
    <h1>Materiais de Estudo</h1>
    <p>Baixe e consulte os materiais disponíveis</p>
</div>

<div class="materiais-container">
    <div class="material-item">
        <span class="material-icon">📕</span>
        <h3>PDF - Introdução ao Laravel</h3>
        <p>Guia completo de iniciação ao Laravel</p>
        <a href="#" class="btn btn-secondary">Baixar PDF</a>
    </div>
    
    <div class="material-item">
        <span class="material-icon">📗</span>
        <h3>Slides - PHP Avançado</h3>
        <p>Apresentação em slides sobre conceitos avançados</p>
        <a href="#" class="btn btn-secondary">Baixar Slides</a>
    </div>
    
    <div class="material-item">
        <span class="material-icon">📘</span>
        <h3>Videoaula - Banco de Dados</h3>
        <p>Vídeo explicativo sobre estrutura de dados</p>
        <a href="#" class="btn btn-secondary">Assistir</a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('pages/aluno_materiais/script.js') }}" defer></script>
@endpush
@endsection
