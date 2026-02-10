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
            <h2>LMS</h2>
            <p>Plataforma de Aulas</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('aluno.dashboard') }}" 
               hx-get="{{ route('aluno.dashboard') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('aluno.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                Dashboard
            </a>
                <a href="{{ route('aluno.cursos') }}" 
                    hx-get="{{ route('aluno.cursos') }}"
                    hx-target="#main-content"
                    hx-push-url="true"
                    hx-swap="innerHTML show:top"
                    class="nav-link {{ request()->routeIs('aluno.cursos') ? 'active' : '' }}">
                     <span class="icon">📘</span>
                     Cursos
                </a>
            <a href="{{ route('aluno.aulas') }}" 
               hx-get="{{ route('aluno.aulas') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('aluno.aulas') ? 'active' : '' }}">
                <span class="icon">📚</span>
                Aulas
            </a>
            <a href="{{ route('aluno.materiais') }}" 
               hx-get="{{ route('aluno.materiais') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('aluno.materiais') ? 'active' : '' }}">
                <span class="icon">📄</span>
                Materiais
            </a>
            <a href="{{ route('aluno.notas') }}" 
               hx-get="{{ route('aluno.notas') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('aluno.notas') ? 'active' : '' }}">
                <span class="icon">✓</span>
                Notas
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" 
               hx-get="{{ route('profile.edit') }}"
               hx-target="#aluno-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="user-profile">
                <span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
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
