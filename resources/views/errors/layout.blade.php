<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') · {{ config('app.name', 'Plataforma de Aulas') }}</title>
    @include('partials.site-icons')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('pages/errors/style.css') }}?v=20261002e">
</head>
<body class="error-page">
    <div class="error-scene">
        <div class="error-glow" aria-hidden="true"></div>
        <main class="error-card">
            <p class="error-brand">Plataforma de Aulas · Codar</p>
            <p class="error-code">@yield('code')</p>
            <h1 class="error-title">@yield('heading')</h1>
            <p class="error-lead">@yield('message')</p>

            <div class="error-actions">
                @yield('actions')
            </div>

            <p class="error-foot">Sem pânico — a gente te coloca de volta no caminho.</p>
        </main>
    </div>
</body>
</html>
