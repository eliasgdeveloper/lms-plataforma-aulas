<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'Plataforma de Aulas'))</title>
        @include('partials.site-icons')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('codar-entrada/login.css') }}?v=20261015h">
        <link rel="stylesheet" href="{{ asset('codar-entrada/landing.css') }}?v=20261015b">
    </head>
    <body>
        <div class="scene">
            <div class="scene-bg" aria-hidden="true"></div>

            <div class="page landing-page">
                <header class="landing-nav">
                    <a href="{{ url('/') }}" class="landing-brand">
                        <img src="{{ asset('codar-entrada/img/brand-mark.svg') }}" width="38" height="38" alt="">
                        <span>Plataforma de Aulas</span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="landing-login-link">Minha área <span aria-hidden="true">→</span></a>
                    @else
                        <a href="{{ route('login') }}" class="landing-login-link">Entrar <span aria-hidden="true">→</span></a>
                    @endauth
                </header>
                @yield('content')
            </div>
        </div>
        <script src="{{ asset('codar-entrada/login.js') }}?v=20261004b"></script>
    </body>
</html>
