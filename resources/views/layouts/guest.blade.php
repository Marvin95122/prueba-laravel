<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'GymControl') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100 min-vh-100 d-flex flex-column antialiased">

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-gray-800 shadow-sm py-3 border-bottom border-success">
            <div class="container-fluid px-4">
                <a class="navbar-brand fw-bold flex items-center gap-2" href="{{ route('home') }}">
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
        <div class="w-full sm:max-w-md px-6 py-8 bg-gray-800 border border-gray-700 shadow-lg sm:rounded-xl">
            {{ $slot }}
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-400 text-center py-4 mt-auto border-t border-gray-800">
        <p class="mb-0 small">&copy; {{ date('Y') }} GymControl</p>
    </footer>

</body>
</html>