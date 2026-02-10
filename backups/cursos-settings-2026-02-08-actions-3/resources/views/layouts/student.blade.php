<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LMS Plataforma') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 text-white shadow-lg hidden md:flex flex-col">
            <div class="p-6 border-b border-blue-800">
                <h1 class="text-2xl font-bold">LMS</h1>
                <p class="text-blue-200 text-sm mt-1">Plataforma de Aulas</p>
            </div>

            <nav class="flex-1 p-6 space-y-2">
                <a href="{{ route('aluno.dashboard') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800 transition">
                    <svg class="inline w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v8m-7-4a2 2 0 110-4 2 2 0 010 4z"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('aluno.aulas') }}" class="block px-4 py-3 rounded-lg bg-blue-800 transition">
                    <svg class="inline w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                    </svg>
                    Aulas
                </a>
                <a href="{{ route('aluno.materiais') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800 transition">
                    <svg class="inline w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"></path>
                    </svg>
                    Materiais
                </a>
                <a href="{{ route('aluno.notas') }}" class="block px-4 py-3 rounded-lg hover:bg-blue-800 transition">
                    <svg class="inline w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Notas
                </a>
            </nav>

            <div class="p-6 border-t border-blue-800">
                <a href="{{ route('profile.edit') }}" class="flex items-center hover:bg-blue-800 px-4 py-3 rounded-lg transition">
                    <div class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center mr-3">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-sm">
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-blue-200 text-xs">{{ auth()->user()->email }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-600 transition text-sm font-semibold">
                        Sair
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar Mobile -->
            <div class="bg-blue-900 text-white p-4 md:hidden flex items-center justify-between">
                <h1 class="text-xl font-bold">LMS</h1>
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Main Area -->
            <main class="flex-1 overflow-auto">
                <div class="p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
@stack('scripts')
</html>
