@extends('layouts.page')

@section('title', config('app.name', 'LMS') . ' - Aluno')

@push('styles')
<link rel="stylesheet" href="{{ asset('layouts/aluno.css') }}">
@endpush

@section('content')
<div class="aluno-wrapper">
    <!-- Sidebar -->
    <aside class="aluno-sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <img src="{{ asset('codar-entrada/img/brand-mark.svg') }}" width="36" height="36" alt="" aria-hidden="true">
                <div>
                    <h2>LMS</h2>
                    <p>Plataforma de Aulas</p>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
                <a href="{{ route('aluno.dashboard') }}"
                    class="nav-link {{ request()->routeIs('aluno.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                Dashboard
            </a>
                <a href="{{ route('aluno.cursos') }}"
                    class="nav-link {{ request()->routeIs('aluno.cursos') ? 'active' : '' }}">
                     <span class="icon">📘</span>
                     Cursos
                </a>
                <a href="{{ route('aluno.aulas') }}"
                    class="nav-link {{ request()->routeIs('aluno.aulas') ? 'active' : '' }}">
                <span class="icon">📚</span>
                Aulas
            </a>
                <a href="{{ route('aluno.materiais') }}"
                    class="nav-link {{ request()->routeIs('aluno.materiais') ? 'active' : '' }}">
                <span class="icon">📄</span>
                Materiais
            </a>
                <a href="{{ route('aluno.notas') }}"
                    class="nav-link {{ request()->routeIs('aluno.notas') ? 'active' : '' }}">
                <span class="icon">✓</span>
                Notas
            </a>
        </nav>

        <div class="sidebar-footer">
                <a href="{{ route('profile.edit') }}" class="user-profile">
                @if (!empty(auth()->user()->lms_badge))
                    <img class="avatar avatar--badge" src="{{ auth()->user()->lms_badge }}" alt="Selo do desafio">
                @else
                    <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-email">{{ auth()->user()->email }}</span>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="aluno-content" class="aluno-main">
        @yield('aluno-content')
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('layouts/aluno.js') }}" defer></script>
@endpush
