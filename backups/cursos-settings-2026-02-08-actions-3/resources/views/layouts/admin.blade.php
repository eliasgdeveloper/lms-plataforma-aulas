@extends('layouts.page')

@section('title', config('app.name', 'LMS') . ' - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('layouts/admin.css') }}">
@endpush

@section('content')
@php
    $adminShell = trim($__env->yieldContent('admin-shell'));
    $isCourseFocus = $adminShell === 'course-focus';
@endphp
<div class="admin-wrapper{{ $isCourseFocus ? ' admin-wrapper--course-focus' : '' }}">
    <!-- Sidebar -->
    @if (! $isCourseFocus)
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>LMS</h2>
            <p>Painel Admin</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                Dashboard
            </a>
            <a href="{{ route('admin.usuarios.index') }}" 
               class="nav-link {{ request()->routeIs('admin.usuarios.index') ? 'active' : '' }}">
                <span class="icon">👤</span>
                Usuários
            </a>
            <a href="{{ route('admin.cursos') }}" 
               class="nav-link {{ request()->routeIs('admin.cursos') ? 'active' : '' }}">
                <span class="icon">🎓</span>
                Cursos
            </a>
            <a href="{{ route('admin.configuracoes') }}" 
               class="nav-link {{ request()->routeIs('admin.configuracoes') ? 'active' : '' }}">
                <span class="icon">⚙️</span>
                Configurações
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" 
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
    @endif

    <!-- Main Content -->
    <main id="admin-content" class="admin-main">
        @yield('admin-content')
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('layouts/admin.js') }}" defer></script>
@endpush
