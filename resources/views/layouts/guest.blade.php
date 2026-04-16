<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
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
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                    <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
                </a>

                <div class="ms-auto">
                    <a class="btn btn-outline-light btn-sm me-2" href="{{ route('login') }}">Iniciar sesión</a>
                    <a class="btn btn-success btn-sm" href="{{ route('register') }}">Registrarse</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container py-5 flex-grow-1 d-flex align-items-center justify-content-center">
        {{ $slot }}
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0 small">&copy; 2026 GymControl</p>
    </footer>

</body>
</html>