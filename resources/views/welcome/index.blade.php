@extends('layouts.page')

@section('title', 'Início - ' . config('app.name'))

@push('styles')
<link rel="stylesheet" href="{{ asset('pages/welcome/style.css') }}">
@endpush

@section('content')
<div class="welcome-container">
    <header class="welcome-header">
        @if (Route::has('login'))
            <nav class="welcome-nav">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        Painel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">
                        Entrar
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Cadastrar
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="welcome-main">
        <div class="welcome-content">
            <h1>Bem-vindo à plataforma {{ config('app.name') }}</h1>
            <p>LMS — Plataforma de Aulas Moderna</p>

            <div class="welcome-features">
                <h2>Primeiros passos</h2>
                <ul class="features-list">
                    <li>
                        <strong>Leia a documentação</strong>
                        <a href="https://laravel.com/docs" target="_blank">
                            Acessar a documentação →
                        </a>
                    </li>
                    <li>
                        <strong>Assista às videoaulas</strong>
                        <a href="https://laracasts.com" target="_blank">
                            Acessar Laracasts →
                        </a>
                    </li>
                    <li>
                        <strong>Explore o ecossistema</strong>
                        <a href="https://laravel.com" target="_blank">
                            Acessar Laravel →
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('pages/welcome/script.js') }}" defer></script>
@endpush
