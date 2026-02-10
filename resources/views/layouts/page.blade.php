<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Página')</title>
    @stack('meta')
    
    <!-- Alpine.js - Interatividade reativa -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #003d82;
            --secondary: #0051b8;
            --text-dark: #1b1b18;
            --text-light: #6b7280;
            --bg-light: #f3f4f6;
            --bg-white: #ffffff;
            --border: #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --text-dark: #f5f5f1;
                --text-light: #d1d5db;
                --bg-light: #1f2937;
                --bg-white: #111827;
                --border: #374151;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Figtree', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .navbar {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .navbar-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .navbar-link {
            text-decoration: none;
            color: var(--text-dark);
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .navbar-link:hover {
            background: var(--bg-light);
        }

        .navbar-btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #002d62;
        }

        .btn-logout {
            background: #dc2626;
            color: white;
        }

        .btn-logout:hover {
            background: #b91c1c;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        main {
            min-height: calc(100vh - 70px);
        }


        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-wrap: wrap;
            }

            .navbar-actions {
                gap: 0.5rem;
                width: 100%;
                margin-top: 1rem;
                flex-wrap: wrap;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">LMS</a>
        
        <div class="navbar-actions">
            @auth
                <div class="user-menu">
                    <span class="navbar-link">{{ auth()->user()->name }}</span>
                          <a href="{{ route('profile.edit') }}" class="navbar-link">Perfil</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="navbar-btn btn-logout">Logout</button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="navbar-btn btn-primary">Login</a>
            @endauth
        </div>
    </nav>

    <!-- Main content com ID para HTMX -->
    <main id="main-content">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
