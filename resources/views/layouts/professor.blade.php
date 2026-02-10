@extends('layouts.page')

@section('title', config('app.name', 'LMS') . ' - Professor')

@push('styles')
<link rel="stylesheet" href="{{ asset('layouts/professor.css') }}">
@endpush

@section('content')
<div class="professor-wrapper">
    <!-- Sidebar -->
    <aside class="professor-sidebar">
        <div class="sidebar-header">
            <h2>LMS</h2>
            <p>Painel do Professor</p>
        </div>

        <nav class="sidebar-nav">
                <a href="{{ route('professor.dashboard') }}"
                    class="nav-link {{ request()->routeIs('professor.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                Dashboard
            </a>
                <a href="{{ route('professor.cursos') }}"
                    class="nav-link {{ request()->routeIs('professor.cursos') ? 'active' : '' }}">
                     <span class="icon">📘</span>
                     Cursos
                </a>
                <a href="{{ route('professor.aulas') }}"
                    class="nav-link {{ request()->routeIs('professor.aulas') ? 'active' : '' }}">
                <span class="icon">✏️</span>
                Criar Aula
            </a>
                <a href="{{ route('professor.materiais') }}"
                    class="nav-link {{ request()->routeIs('professor.materiais') ? 'active' : '' }}">
                <span class="icon">📤</span>
                Postar Material
            </a>
                <a href="{{ route('professor.alunos') }}"
                    class="nav-link {{ request()->routeIs('professor.alunos') ? 'active' : '' }}">
                <span class="icon">👥</span>
                Acompanhar Alunos
            </a>
        </nav>

        <div class="sidebar-footer">
                <a href="{{ route('profile.edit') }}" class="user-profile">
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
    <main id="professor-content" class="professor-main">
        @yield('professor-content')
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('layouts/professor.js') }}" defer></script>
@endpush
