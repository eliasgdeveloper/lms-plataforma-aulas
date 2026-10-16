<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Entrar | {{ config('app.name', 'Plataforma de Aulas') }}</title>
        @include('partials.site-icons')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('codar-entrada/landing.css') }}?v=20261015b">
        <link rel="stylesheet" href="{{ asset('codar-entrada/auth.css') }}?v=20261016">
    </head>
    <body>
        <div class="scene">
            <div class="scene-bg" aria-hidden="true"></div>
            <div class="page auth-site">
                <header class="landing-nav">
                    <a href="{{ url('/') }}" class="landing-brand">
                        <img src="{{ asset('codar-entrada/img/brand-mark.svg') }}" width="38" height="38" alt="">
                        <span>Plataforma de Aulas</span>
                    </a>
                    <a href="{{ url('/') }}" class="landing-login-link">Página inicial <span aria-hidden="true">→</span></a>
                </header>

                <main class="auth-layout">
                    <section class="auth-intro" aria-labelledby="auth-welcome-title">
                        <p class="auth-eyebrow">PLATAFORMA DE AULAS</p>
                        <h1 id="auth-welcome-title">Seu próximo passo começa aqui.</h1>
                        <p>Acesse seus cursos, acompanhe seu progresso e transforme suas ideias em prática.</p>
                        <div class="auth-highlight">
                            <span class="auth-highlight__dot" aria-hidden="true"></span>
                            <span>Aulas, prática e progresso em um só lugar.</span>
                        </div>
                    </section>

                    <section class="auth-card" aria-label="Formulário de autenticação">
                        {{ $slot }}
                    </section>
                </main>
                <p class="auth-footer">Aprenda, construa e publique.</p>
            </div>
        </div>
        <script src="{{ asset('codar-entrada/login.js') }}?v=20261015"></script>
    </body>
</html>
