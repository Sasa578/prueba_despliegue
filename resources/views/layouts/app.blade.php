<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agenda de Pagos')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span class="brand-icon">💸</span>
                <div>
                    <h1>Agenda de Pagos</h1>
                    <p>Recordatorios de servicios</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span>📊</span> Panel principal
                </a>
                <a href="{{ route('pagos.index') }}" class="nav-link {{ request()->routeIs('pagos.*') ? 'active' : '' }}">
                    <span>💳</span> Pagos
                </a>
                <a href="{{ route('servicios.index') }}" class="nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}">
                    <span>🧾</span> Servicios
                </a>
                <a href="{{ route('personas.index') }}" class="nav-link {{ request()->routeIs('personas.*') ? 'active' : '' }}">
                    <span>👥</span> Personas
                </a>
            </nav>

            <div class="sidebar-footer">
                <p>Laravel + Vite + PostgreSQL</p>
            </div>
        </aside>

        <main class="content">
            <header class="topbar">
                <h2>@yield('page_title')</h2>
                @yield('actions')
            </header>

            @if (session('success'))
                <div class="alert alert-success">
                    ✅ {{ session('success') }}
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    ⚠️ {{ session('error') }}
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    ⚠️ Revisa el formulario: hay campos con errores.
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
