<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GymControl') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* TUS COLORES ORIGINALES DEL MODO CLARO */
        body { background-color: #f4f6f9 !important; color: #212529 !important; }
        .navbar-custom { background-color: #212529 !important; border-bottom: 3px solid #1f7c34 !important; }
        .text-dark-custom { color: #212529 !important; }
        footer { background-color: #212529 !important; color: white !important; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm py-3">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                    <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuGym">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menuGym">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold text-white' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active fw-bold text-white' : '' }}" href="{{ route('clientes.index') }}">
                                <i class="bi bi-people-fill"></i> Clientes
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="bi bi-images"></i> Galería
                            </a>
                        </li>

                        <li class="nav-item d-flex align-items-center ms-lg-4 border-start border-secondary ps-lg-4 mt-3 mt-lg-0 pt-3 pt-lg-0">
                            <span class="text-light me-3 fw-semibold">
                                <i class="bi bi-person-circle text-success me-1"></i> {{ Auth::user()->name ?? 'Usuario' }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm fw-bold px-3 d-flex align-items-center gap-2" type="submit">
                                    <i class="bi bi-box-arrow-right"></i> Salir
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow-1 py-4">
        {{ $slot }}
    </main>

    <footer class="text-center py-4 mt-auto">
        <p class="mb-0 small">&copy; {{ date('Y') }} GymControl. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>