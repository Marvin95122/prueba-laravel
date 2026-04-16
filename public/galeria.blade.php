<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymControl - Galería</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .foto-card { transition: transform .25s ease, box-shadow .25s ease; }
        .foto-card:hover { transform: translateY(-6px); box-shadow: 0 1rem 2rem rgba(0,0,0,.12); }
        .carousel-item img { height: 420px; object-fit: cover; }
        .card-img-top { height: 220px; object-fit: cover; background: #e9ecef; }
    </style>
</head>
<body class="bg-light min-vh-100 d-flex flex-column">

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3 border-bottom border-success">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
            </a>

            <div class="ms-auto d-flex gap-2">
                @auth
                    <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">Salir</button>
                    </form>
                @else
                    <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Iniciar sesión</a>
                    <a class="btn btn-success btn-sm" href="{{ route('register') }}">Registrarse</a>
                @endauth
            </div>
        </div>
    </nav>
</header>

<main class="container-fluid px-4 my-4 flex-grow-1">

    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark display-6">
            <i class="bi bi-images text-success"></i> Galería de instalaciones
        </h1>
        <p class="text-muted">Conoce las áreas, equipo y servicios de GymControl.</p>
    </div>

    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div id="carruselGym" class="carousel slide shadow rounded overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('imagenes/pesasocardio.jpg') }}" class="d-block w-100" alt="Pesas y cardio">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('imagenes/clase-colectiva.jpg') }}" class="d-block w-100" alt="Clases grupales">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('imagenes/entrenador.jpg') }}" class="d-block w-100" alt="Entrenador">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carruselGym" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carruselGym" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </div>

    @php
        $fotos = [
            ['titulo' => 'Área de Cardio', 'desc' => 'Caminadoras, bicicletas y elípticas.', 'img' => 'imagenes/cardio.jpg'],
            ['titulo' => 'Peso Integrado', 'desc' => 'Máquinas para trabajo seguro por grupo muscular.', 'img' => 'imagenes/maquinas.jpg'],
            ['titulo' => 'Crossfit Zone', 'desc' => 'Zona funcional con cuerdas, cajas y pesas rusas.', 'img' => 'imagenes/crossfit.jpg'],
            ['titulo' => 'Vestidores', 'desc' => 'Regaderas, lockers y vestidores amplios.', 'img' => 'imagenes/vestidores.jpg'],
            ['titulo' => 'Suplementos', 'desc' => 'Área de venta de suplementos y bebidas.', 'img' => 'imagenes/suplementos.jpg'],
        ];
    @endphp

    <div class="row g-4">
        @foreach($fotos as $foto)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm foto-card">
                    <img src="{{ asset($foto['img']) }}" class="card-img-top" alt="{{ $foto['titulo'] }}">
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $foto['titulo'] }}</h5>
                        <p class="text-muted small mb-0">{{ $foto['desc'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</main>

<footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0 small">&copy; 2026 GymControl</p>
</footer>

</body>
</html>