<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymControl - Bienvenidos</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body { 
            background-color: #0f172a !important; 
            color: #f8fafc !important;
        }
        .navbar {
            background-color: #1e293b !important;
            border-bottom: 3px solid #198754 !important; 
        }
        .hero-section { padding: 60px 0; text-align: center; }
        .card-gym {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
            border-radius: 12px; overflow: hidden;
            transition: transform 0.3s ease, shadow 0.3s ease;
        }
        .card-gym:hover { transform: translateY(-10px); box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        .card-gym img { height: 200px; object-fit: cover; filter: brightness(0.8); }
        .card-gym .card-title { color: #ffffff; font-weight: bold; }
        .card-gym .card-text { color: #94a3b8; }
        .badge-gym { background-color: rgba(25, 135, 84, 0.2); color: #22c55e; border: 1px solid #198754; }
        footer { background-color: #020617; border-top: 1px solid #1e293b; padding: 20px 0; color: #64748b; }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
            </a>
            
            <div class="ms-auto d-flex gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-success fw-bold">Ir al Panel</a>
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger fw-bold">Salir</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light fw-bold">Iniciar Sesión</a>
                    @endauth
                @endif
            </div>

        </div>
    </nav>

    <header class="hero-section container">
        <h1 class="display-4 fw-extrabold mt-4">Nuestras <span class="text-success">Instalaciones</span></h1>
        <p class="lead text-muted mx-auto" style="max-width: 700px;">
            Equipamiento de última generación y áreas diseñadas para que alcances tu máximo potencial físico.
        </p>
    </header>

    <main class="container mb-5 flex-grow-1">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <article class="card card-gym h-100 shadow-sm">
                    <img src="{{ asset('imagenes/cardio.jpg') }}" class="card-img-top" alt="Cardio">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Área de Cardio</h5>
                        <p class="card-text small flex-grow-1">Caminadoras y elípticas con tecnología de punta.</p>
                        <span class="badge badge-gym mt-2 p-2">Plan Básico</span>
                    </div>
                </article>
            </div>

            <div class="col-md-6 col-lg-3">
                <article class="card card-gym h-100 shadow-sm">
                    <img src="{{ asset('imagenes/maquinas.jpg') }}" class="card-img-top" alt="Máquinas">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Peso Integrado</h5>
                        <p class="card-text small flex-grow-1">Máquinas seguras para todos los niveles.</p>
                        <span class="badge badge-gym mt-2 p-2">Plan Básico</span>
                    </div>
                </article>
            </div>

            <div class="col-md-6 col-lg-3">
                <article class="card card-gym h-100 shadow-sm">
                    <img src="{{ asset('imagenes/crossfit.jpg') }}" class="card-img-top" alt="Crossfit">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Zona Crossfit</h5>
                        <p class="card-text small flex-grow-1">Entrenamiento funcional de alta intensidad.</p>
                        <span class="badge badge-gym mt-2 p-2" style="color: #3b82f6; border-color: #3b82f6; background: rgba(59,130,246,0.1);">Plan Plus</span>
                    </div>
                </article>
            </div>

            <div class="col-md-6 col-lg-3">
                <article class="card card-gym h-100 shadow-sm">
                    <img src="{{ asset('imagenes/vestidores.jpg') }}" class="card-img-top" alt="Vestidores">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Vestidores</h5>
                        <p class="card-text small flex-grow-1">Áreas limpias, lockers y agua caliente.</p>
                        <span class="badge badge-gym mt-2 p-2" style="color: #a855f7; border-color: #a855f7; background: rgba(168,85,247,0.1);">Todos los planes</span>
                    </div>
                </article>
            </div>
        </div>
    </main>

    <footer class="text-center mt-auto">
        <div class="container">
            <p class="mb-0 small">&copy; {{ date('Y') }} GymControl. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>