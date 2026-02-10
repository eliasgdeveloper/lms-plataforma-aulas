@extends('layouts.page')

@section('title', config('app.name', 'LMS') . ' - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('layouts/admin.css') }}">
@endpush

@section('content')
<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2>LMS</h2>
            <p>Painel Admin</p>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" 
               hx-get="{{ route('admin.dashboard') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span>
                Dashboard
            </a>
                <a href="{{ route('admin.usuarios.index') }}" 
                    hx-get="{{ route('admin.usuarios.index') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
                    class="nav-link {{ request()->routeIs('admin.usuarios.index') ? 'active' : '' }}">
                <span class="icon">👤</span>
                Usuários
            </a>
            <a href="{{ route('admin.cursos') }}" 
               hx-get="{{ route('admin.cursos') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('admin.cursos') ? 'active' : '' }}">
                <span class="icon">🎓</span>
                Cursos
            </a>
            <a href="{{ route('admin.configuracoes') }}" 
               hx-get="{{ route('admin.configuracoes') }}"
               hx-target="#main-content"
               hx-push-url="true"
               hx-swap="innerHTML show:top"
               class="nav-link {{ request()->routeIs('admin.configuracoes') ? 'active' : '' }}">
                <span class="icon">⚙️</span>
                Configurações
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('profile.edit') }}" 
               hx-get="{{ route('profile.edit') }}"
               hx-target="#admin-content"
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
    <main id="admin-content" class="admin-main">
        @yield('admin-content')
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('layouts/admin.js') }}" defer></script>
@endpush
