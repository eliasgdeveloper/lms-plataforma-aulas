@extends('layouts.aluno')

@section('aluno-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/aluno_dashboard/style.css') }}?v=20261002c">
@endpush

<div class="dashboard-hero">
    <h1>Bem-vindo, {{ auth()->user()->name }}!</h1>
    <p>Acesse suas aulas, materiais e notas.</p>
</div>

<section class="chama-codar {{ !empty($chamaLit) ? 'is-lit' : 'is-dim' }}" aria-label="Chama Codar">
    <div class="chama-codar__flame" aria-hidden="true"></div>
    <div class="chama-codar__body">
        <h2>Chama Codar</h2>
        @if (!empty($chamaLit))
            <p>Chama acesa hoje. Sequência: <strong>{{ (int) ($chamaStreak ?? 0) }}</strong> dia(s).</p>
        @else
            <p>Estude um item ou faça um quiz hoje para acender a chama.</p>
            <p class="chama-codar__streak">Última sequência: <strong>{{ (int) ($chamaStreak ?? 0) }}</strong> dia(s)</p>
        @endif
    </div>
</section>

<nav class="dashboard-nav">
    <div class="nav-card">
        <a href="{{ route('aluno.cursos') }}">
            <i class="fa-solid fa-book-open icon" aria-hidden="true"></i>
            <h3>Meus cursos</h3>
            <p>Continue a trilha PHP</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('aluno.aulas') }}">
            <i class="fa-solid fa-graduation-cap icon" aria-hidden="true"></i>
            <h3>Minhas Aulas</h3>
            <p>Acesse todas as suas aulas</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('aluno.materiais') }}">
            <i class="fa-solid fa-file-lines icon" aria-hidden="true"></i>
            <h3>Materiais</h3>
            <p>Baixe materiais de apoio</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('aluno.notas') }}">
            <i class="fa-solid fa-check icon" aria-hidden="true"></i>
            <h3>Notas</h3>
            <p>Veja suas notas e desempenho</p>
        </a>
    </div>
</nav>

@push('scripts')
<script src="{{ asset('pages/aluno_dashboard/script.js') }}" defer></script>
@endpush
@endsection
