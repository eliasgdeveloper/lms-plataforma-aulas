@extends('layouts.page')

@section('title', 'Welcome - ' . config('app.name'))

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
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <main class="welcome-main">
        <div class="welcome-content">
            <h1>Welcome to {{ config('app.name') }}</h1>
            <p>LMS — Plataforma de Aulas Moderna</p>

            <div class="welcome-features">
                <h2>Getting Started</h2>
                <ul class="features-list">
                    <li>
                        <strong>Read the Documentation</strong>
                        <a href="https://laravel.com/docs" target="_blank">
                            Visit Docs →
                        </a>
                    </li>
                    <li>
                        <strong>Watch Video Tutorials</strong>
                        <a href="https://laracasts.com" target="_blank">
                            Visit Laracasts →
                        </a>
                    </li>
                    <li>
                        <strong>Explore the Ecosystem</strong>
                        <a href="https://laravel.com" target="_blank">
                            Visit Laravel →
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
