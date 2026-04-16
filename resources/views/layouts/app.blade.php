<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GymControl') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Fondo claro del sistema original */
        body { background-color: #f4f6f9 !important; color: #212529; }
        
        /* Tonos verdes de GymControl */
        .border-success { border-color: #1f7c34 !important; }
        .text-success { color: #1f7c34 !important; }
        .btn-success { background-color: #1f7c34 !important; border-color: #1f7c34 !important; }
        
        /* Ajustes de color para la barra oscura */
        .navbar-dark .navbar-nav .nav-link { color: rgba(255, 255, 255, 0.7); }
        .navbar-dark .navbar-nav .nav-link:hover { color: #1f7c34; }
        .navbar-dark .navbar-nav .nav-link.active { color: #ffffff; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    
    @include('layouts.navigation')

    <main class="flex-grow-1 py-4">
        {{ $slot }}
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <p class="mb-0 small">&copy; {{ date('Y') }} GymControl. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>