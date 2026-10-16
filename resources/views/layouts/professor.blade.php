@extends('layouts.page')

@section('title', config('app.name', 'LMS') . ' - Professor')

@push('styles')
<link rel="stylesheet" href="{{ asset('layouts/professor.css') }}">
@endpush

@section('content')
@php
    $professorShell = trim($__env->yieldContent('professor-shell'));
    $isCourseFocus = $professorShell === 'course-focus';
@endphp
<div class="professor-wrapper{{ $isCourseFocus ? ' professor-wrapper--course-focus' : '' }}">
    @unless ($isCourseFocus)
    <!-- Sidebar -->
    <aside class="professor-sidebar">
        <div class="sidebar-header">
            <h2>LMS</h2>
            <p>Painel do Professor</p>
        </div>

        <nav class="sidebar-nav">
                <a href="{{ route('professor.dashboard') }}"
                    class="nav-link {{ request()->routeIs('professor.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-column icon" aria-hidden="true"></i>
                Painel
            </a>
                <a href="{{ route('professor.cursos') }}"
                    class="nav-link {{ request()->routeIs('professor.cursos') ? 'active' : '' }}">
                     <i class="fa-solid fa-book icon" aria-hidden="true"></i>
                     Cursos
                </a>
                <a href="{{ route('professor.aulas') }}"
                    class="nav-link {{ request()->routeIs('professor.aulas') ? 'active' : '' }}">
                <i class="fa-solid fa-pen icon" aria-hidden="true"></i>
                Criar Aula
            </a>
                <a href="{{ route('professor.materiais') }}"
                    class="nav-link {{ request()->routeIs('professor.materiais') ? 'active' : '' }}">
                <i class="fa-solid fa-upload icon" aria-hidden="true"></i>
                Postar Material
            </a>
                <a href="{{ route('professor.alunos') }}"
                    class="nav-link {{ request()->routeIs('professor.alunos') ? 'active' : '' }}">
                <i class="fa-solid fa-users icon" aria-hidden="true"></i>
                Acompanhar Alunos
            </a>
                <a href="{{ route('view-as.edit') }}"
                    class="nav-link {{ request()->routeIs('view-as.edit') ? 'active' : '' }}">
                <i class="fa-solid fa-eye icon" aria-hidden="true"></i>
                Mudar papel
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
                <button type="submit" class="logout-btn">Sair</button>
            </form>
        </div>
    </aside>
    @endunless

    <!-- Main Content -->
    <main id="professor-content" class="professor-main">
        @yield('professor-content')
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('layouts/professor.js') }}" defer></script>
@endpush
