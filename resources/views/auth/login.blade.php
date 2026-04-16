<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GymControl - Acceso al Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body { background-color: #f4f6f9; }
    .login-card { border-radius: 10px; border-top: 5px solid #1f7c34; background-color: white; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      <div class="text-center mb-4">
        <h1 class="fw-bold text-dark"><i class="bi bi-heart-pulse-fill text-success"></i> GymControl</h1>
        <p class="text-muted">Gestión integral de gimnasios</p>
      </div>
      <div class="card shadow-sm login-card border-0">
        <div class="card-body p-4">
          @if ($errors->any())
              <div class="alert alert-danger py-2 text-sm">
                  <ul class="mb-0 ps-3">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-bold">Correo electrónico</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="ejemplo@gym.com" required autofocus>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label fw-bold">Contraseña</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
              </div>
            </div>
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label text-muted" for="remember">Recordar mi sesión</label>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success btn-lg fw-bold" style="background-color: #1f7c34; border-color: #1f7c34;">
                Acceder al panel
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="text-center mt-3 text-muted small">
        &copy; {{ date('Y') }} GymControl
      </div>
    </div>
  </div>
</div>
</body>
</html>