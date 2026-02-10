<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Curso')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="aluno-course-body">
    <header class="aluno-course-topbar">
        <div class="topbar-left">
            <a href="{{ route('aluno.dashboard') }}" class="topbar-logo">LMS</a>
            <nav class="topbar-nav">
                <a href="{{ route('aluno.cursos') }}">Cursos</a>
                <a href="{{ route('aluno.aulas') }}">Aulas</a>
                <a href="{{ route('aluno.materiais') }}">Materiais</a>
                <a href="{{ route('aluno.notas') }}">Notas</a>
            </nav>
        </div>
        <div class="topbar-right">
            <span class="topbar-user">{{ auth()->user()->name }}</span>
            <a href="{{ route('profile.edit') }}" class="topbar-link">Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-logout">Logout</button>
            </form>
        </div>
    </header>

    <main class="aluno-course-main">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
