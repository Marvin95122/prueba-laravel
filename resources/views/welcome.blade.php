<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GymControl - Galería de Instalaciones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body { background-color: #f4f6f9; }
    .navbar { border-bottom: 3px solid #1f7c34; background-color: #212529 !important; }
    .foto-card { transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; border: none; }
    .foto-card:hover { transform: translateY(-7px); box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important; }
    .card-img-top { height: 200px; object-fit: cover; background-color: #e9ecef; }
    .badge-custom { font-size: 0.85rem; padding: 0.4rem 0.6rem; border-radius: 5px; font-weight: 600; }
    .bg-plan-basico { background-color: #e8f5e9; color: #1f7c34; border: 1px solid #1f7c34; }
    .bg-plan-plus { background-color: #e3f2fd; color: #0d6efd; border: 1px solid #0d6efd; }
    .bg-plan-todos { background-color: #f3e5f5; color: #6f42c1; border: 1px solid #6f42c1; }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">
        <i class="bi bi-heart-pulse-fill text-success"></i> GymControl
      </a>
      <div class="ms-auto d-flex align-items-center gap-3">
          @if (Route::has('login'))
              @auth
                  <a href="{{ url('/dashboard') }}" class="btn fw-bold text-white" style="background-color: #1f7c34;">Ir al Panel</a>
                  <form method="POST" action="{{ route('logout') }}" class="m-0">
                      @csrf
                      <button type="submit" class="btn btn-danger fw-bold shadow-sm">Cerrar sesión</button>
                  </form>
              @else
                  <a href="{{ route('login') }}" class="btn btn-outline-light fw-bold">Iniciar Sesión</a>
              @endauth
          @endif
      </div>
    </div>
  </nav>

  <header class="text-center py-5">
      <h1 class="display-5 fw-bold text-dark">Nuestras <span style="color: #1f7c34;">Instalaciones</span></h1>
      <p class="lead text-muted mx-auto" style="max-width: 700px;">
          Equipamiento de última generación y áreas diseñadas para que alcances tu máximo potencial físico.
      </p>
  </header>

  <main class="container mb-5 flex-grow-1">
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <article class="card foto-card h-100 shadow-sm">
          <img src="{{ asset('imagenes/cardio.jpg') }}" class="card-img-top" alt="Cardio">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold text-dark">Área de Cardio</h5>
            <p class="card-text small text-muted flex-grow-1">Caminadoras y elípticas con pantallas integradas.</p>
            <div><span class="badge-custom bg-plan-basico">Plan Básico</span></div>
          </div>
        </article>
      </div>
      <div class="col-md-6 col-lg-3">
        <article class="card foto-card h-100 shadow-sm">
          <img src="{{ asset('imagenes/maquinas.jpg') }}" class="card-img-top" alt="Máquinas">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold text-dark">Peso Integrado</h5>
            <p class="card-text small text-muted flex-grow-1">Máquinas seguras para todos los niveles.</p>
            <div><span class="badge-custom bg-plan-basico">Plan Básico</span></div>
          </div>
        </article>
      </div>
      <div class="col-md-6 col-lg-3">
        <article class="card foto-card h-100 shadow-sm">
          <img src="{{ asset('imagenes/crossfit.jpg') }}" class="card-img-top" alt="Crossfit">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold text-dark">Zona Crossfit</h5>
            <p class="card-text small text-muted flex-grow-1">Entrenamiento funcional de alta intensidad.</p>
            <div><span class="badge-custom bg-plan-plus">Plan Plus</span></div>
          </div>
        </article>
      </div>
      <div class="col-md-6 col-lg-3">
        <article class="card foto-card h-100 shadow-sm">
          <img src="{{ asset('imagenes/vestidores.jpg') }}" class="card-img-top" alt="Vestidores">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title fw-bold text-dark">Vestidores</h5>
            <p class="card-text small text-muted flex-grow-1">Áreas limpias, lockers y agua caliente.</p>
            <div><span class="badge-custom bg-plan-todos">Todos los planes</span></div>
          </div>
        </article>
      </div>
    </div>
  </main>

  <footer class="bg-dark text-white text-center py-3 mt-auto">
    <p class="mb-0 small">&copy; {{ date('Y') }} GymControl. Todos los derechos reservados.</p>
  </footer>
</body>
</html>