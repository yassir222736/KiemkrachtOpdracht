<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kiemkracht') }} — @yield('title', 'Kassaticket')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//api.fontshare.com">

    <!-- Scripts & Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        {{-- Navigatiebalk met Kiemkracht huisstijl --}}
        <nav class="navbar navbar-expand-md navbar-kiemkracht">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">KIEMKRACHT</a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarMain" aria-controls="navbarMain"
                        aria-expanded="false" aria-label="Toggle navigatie">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tickets.create') }}">Kassabon Indienen</a>
                        </li>
                        @auth
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.tickets.index') }}">Beheer</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Inloggen</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.tickets.index') }}">Beheer</a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Uitloggen
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Hoofdinhoud --}}
        <main class="flex-grow-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="footer-kiemkracht text-center">
            <div class="container">
                <p class="mb-1"><strong>KiemKracht vzw</strong> — Mens, Natuur en Toekomst</p>
                <p class="mb-0 small">
                    <a href="https://kiemkracht.org" target="_blank" rel="noopener">kiemkracht.org</a>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
