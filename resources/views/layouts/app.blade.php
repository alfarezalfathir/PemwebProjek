<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Restoran Kelompok') }}</title>

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-body-tertiary">
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-fire me-2 text-warning"></i> RestoEnak
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-warning' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>

                            @role('superadmin|manager')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products.*') ? 'active text-warning' : '' }}" href="{{ route('products.index') }}">
                                    <i class="bi bi-box-seam me-1"></i>Kelola Menu
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active text-warning' : '' }}" href="{{ route('categories.index') }}">
                                    <i class="bi bi-tags me-1"></i>Kategori
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tables.*') ? 'active text-warning' : '' }}" href="{{ route('tables.index') }}">
                                    <i class="bi bi-display me-1"></i>Meja
                                </a>
                            </li>
                            <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('users.*') ? 'active text-warning' : '' }}" href="{{ route('users.index') }}">
        <i class="bi bi-people me-1"></i>Kelola User
    </a>
</li>
                            @endrole

                            @role('superadmin|manager|cashier')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active text-warning' : '' }}" href="{{ route('transactions.index') }}">
                                    <i class="bi bi-cash-coin me-1"></i>Kasir & Order
                                </a>
                            </li>
                            @endrole

                            @role('customer')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('order.*') ? 'active text-warning' : '' }}" href="{{ route('order.index') }}">
                                    <i class="bi bi-cup-hot-fill me-1"></i>Pesan Makanan
                                </a>
                            </li>
                            @endrole
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-2">
                            <button class="btn btn-link nav-link text-white" id="darkModeBtn">
                                <i class="bi bi-moon-stars-fill" id="darkModeIcon"></i>
                            </button>
                        </li>

                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="btn btn-warning ms-2 btn-sm text-dark fw-bold" href="{{ route('register') }}">Daftar</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold text-white" href="#" role="button" data-bs-toggle="dropdown">
                                    Halo, {{ Auth::user()->name }} 
                                    <span class="badge bg-warning text-dark ms-1">{{ Auth::user()->getRoleNames()->first() }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger fw-bold">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <footer class="mt-auto py-3 bg-dark text-center text-white shadow-sm">
        <div class="container small">
            <span class="text-white-50">© 2026 RestoEnak - Kelompok Laravel 12</span>
        </div>
    </footer>

    <script>
        const btn = document.getElementById('darkModeBtn');
        const icon = document.getElementById('darkModeIcon');
        const html = document.documentElement;

        // Cek Local Storage saat load
        if (localStorage.getItem('theme') === 'dark') {
            html.setAttribute('data-bs-theme', 'dark');
            icon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
        }

        // Event Listener Tombol
        btn.addEventListener('click', () => {
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                icon.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                icon.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
                localStorage.setItem('theme', 'dark');
            }
        });
    </script>
</body>
</html>