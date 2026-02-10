@extends('layouts.admin')

@section('admin-content')
@push('styles')
<link rel="stylesheet" href="{{ asset('pages/admin_dashboard/style.css') }}">
@endpush

<div class="dashboard-hero">
    <h1>Bem-vindo, Administrador {{ auth()->user()->name }}!</h1>
    <p>Gerencie usuários, cursos e configurações da plataforma.</p>
</div>

<nav class="dashboard-nav">
    <div class="nav-card">
        <a href="{{ route('admin.usuarios') }}">
            <span class="icon">👤</span>
            <h3>Gerenciar Usuários</h3>
            <p>Adicione, edite ou remova usuários</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('admin.cursos') }}">
            <span class="icon">🎓</span>
            <h3>Gerenciar Cursos</h3>
            <p>Crie e organize os cursos</p>
        </a>
    </div>
    <div class="nav-card">
        <a href="{{ route('admin.configuracoes') }}">
            <span class="icon">⚙️</span>
            <h3>Configurações</h3>
            <p>Ajuste as configurações da plataforma</p>
        </a>
    </div>
</nav>

@push('scripts')
<script src="{{ asset('pages/admin_dashboard/script.js') }}" defer></script>
@endpush
@endsection



