@extends('layouts.professor')

@section('professor-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/professor_dashboard/style.css') }}">
@endpush

<div class="dashboard-hero">
    <h1>Bem-vindo, Professor {{ auth()->user()->name }}!</h1>
    <p>Crie aulas, compartilhe materiais e acompanhe o progresso dos seus alunos.</p>
</div>

<nav class="dashboard-nav">
    <div class="nav-card">
        <a href="{{ route('professor.aulas') }}">
            <i class="fa-solid fa-pen icon" aria-hidden="true"></i>
            <h3>Criar Aula</h3>
            <p>Crie e gerencie suas aulas</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('professor.materiais') }}">
            <i class="fa-solid fa-upload icon" aria-hidden="true"></i>
            <h3>Postar Material</h3>
            <p>Compartilhe recursos com alunos</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('professor.alunos') }}">
            <i class="fa-solid fa-users icon" aria-hidden="true"></i>
            <h3>Acompanhar Alunos</h3>
            <p>Veja progresso e desempenho</p>
        </a>
    </div>
</nav>

@push('scripts')
<script src="{{ asset('pages/professor_dashboard/script.js') }}"></script>
@endpush
@endsection

