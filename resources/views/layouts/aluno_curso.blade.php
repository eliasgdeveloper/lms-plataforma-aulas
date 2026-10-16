<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Curso')</title>
    @include('partials.site-icons')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="aluno-course-body">
    @include('partials.view-as-banner')
    <header class="aluno-course-topbar">
        <div class="topbar-left">
            <a href="{{ route('aluno.dashboard') }}" class="topbar-logo">
                <img src="{{ asset('codar-entrada/img/brand-mark.svg') }}" width="28" height="28" alt="" aria-hidden="true">
                LMS
            </a>
            <nav class="topbar-nav">
                <a href="{{ route('aluno.dashboard') }}" class="{{ request()->routeIs('aluno.dashboard') ? 'is-active' : '' }}">Início</a>
                <a href="{{ route('aluno.cursos') }}" class="{{ request()->routeIs('aluno.cursos*') ? 'is-active' : '' }}">Cursos</a>
                <button type="button" onclick="history.back()">Voltar</button>
                <button type="button" onclick="history.forward()">Avançar</button>
            </nav>
        </div>
        <div class="topbar-right">
            @if (!empty(auth()->user()->lms_badge))
                <img class="topbar-badge" src="{{ auth()->user()->lms_badge }}" alt="Selo" width="36" height="36">
            @endif
            <span class="topbar-user">{{ auth()->user()->name }}</span>
            <a href="{{ route('profile.edit') }}" class="topbar-link">Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-logout">Sair</button>
            </form>
        </div>
    </header>

    <main class="aluno-course-main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
