@extends('layouts.aluno')

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_dashboard/style.css') }}">
@endpush

<div class="dashboard-hero">
    <h1>Bem-vindo, Aluno {{ auth()->user()->name }}!</h1>
    <p>Acesse suas aulas, materiais e notas.</p>
</div>

<nav class="dashboard-nav">
    <div class="nav-card">
        <a href="{{ route('aluno.aulas') }}">
            <span class="icon">📚</span>
            <h3>Minhas Aulas</h3>
            <p>Acesse todas as suas aulas</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('aluno.materiais') }}">
            <span class="icon">📄</span>
            <h3>Materiais</h3>
            <p>Baixe materiais de apoio</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('aluno.notas') }}">
            <span class="icon">✓</span>
            <h3>Notas</h3>
            <p>Veja suas notas e desempenho</p>
        </a>
    </div>
</nav>

@push('scripts')
<script src="{{ asset('pages/aluno_dashboard/script.js') }}" defer></script>
@endpush
@endsection
