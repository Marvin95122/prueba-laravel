<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GymControl') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light min-vh-100 d-flex flex-column">

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3 border-bottom border-success">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                    <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuGym">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menuGym">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                               href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}"
                               href="{{ route('clientes.index') }}">
                                <i class="bi bi-people-fill"></i> Clientes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('checkins.*') ? 'active' : '' }}"
                               href="{{ route('checkins.create') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Check-in
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                               href="{{ route('home') }}">
                                <i class="bi bi-images"></i> Galería
                            </a>
                        </li>

                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name ?? 'Usuario' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text text-muted small">
                                        Rol: {{ Auth::user()->role ?? 'sin rol' }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    @isset($header)
        <section class="bg-white border-bottom shadow-sm">
            <div class="container-fluid px-4 py-3">
                {{ $header }}
            </div>
        </section>
    @endisset

    <main class="flex-grow-1 py-4">
        {{ $slot }}
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0 small">&copy; 2026 GymControl</p>
    </footer>

</body>
</html>