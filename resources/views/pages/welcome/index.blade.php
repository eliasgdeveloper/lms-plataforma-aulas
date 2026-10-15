<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Plataforma de Aulas') }}</title>
    @include('partials.site-icons')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('codar-entrada/login.css') }}?v=20261004g">
</head>
<body>
    <div class="scene">
        <div class="scene-bg" aria-hidden="true">
            <div class="slides">
                <div class="slide"></div>
                <div class="slide"></div>
                <div class="slide"></div>
                <div class="slide"></div>
                <div class="slide"></div>
            </div>
            <div class="veil"></div>
            <div class="grain"></div>
            <div class="orbs">
                <div class="orb orb-a"></div>
                <div class="orb orb-b"></div>
                <div class="orb orb-c"></div>
                <div class="glass-panel one"></div>
                <div class="glass-panel two"></div>
            </div>
            <canvas id="network"></canvas>
        </div>

        <div class="page">
            <main class="shell">
                @include('partials.codar-entrada-copy')
                <div class="card">
                    @auth
                        <h2>Você já entrou</h2>
                        <p>Siga para a área logada sem trocar de conta.</p>
                        <a class="dash-link" href="{{ route('dashboard') }}">Ir ao painel</a>
                    @else
                        @include('auth.partials.codar-login-card')
                    @endauth
                </div>
            </main>
            @include('partials.codar-entrada-more')
        </div>
    </div>
    <script src="{{ asset('codar-entrada/login.js') }}?v=20261004b"></script>
</body>
</html>
