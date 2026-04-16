<x-app-layout>
    <style>
        /* Estilos originales de tus tarjetas */
        .kpi-card { background-color: white; border: none; border-radius: 8px; border-left: 5px solid; transition: transform 0.2s; }
        .kpi-card:hover { transform: translateY(-4px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
        .border-l-primary { border-left-color: #0d6efd !important; }
        .border-l-success { border-left-color: #198754 !important; }
        .border-l-danger { border-left-color: #dc3545 !important; }
        .border-l-warning { border-left-color: #ffc107 !important; }
        .card-custom { background-color: white; border: 1px solid #dee2e6; border-radius: 10px; }
    </style>

    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark-custom fw-bold"><i class="bi bi-grid-1x2-fill text-success me-2"></i> Panel de Control</h2>
                <small class="text-muted">Resumen general de operaciones del gimnasio.</small>
            </div>
            <a href="{{ route('clientes.create') }}" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Nuevo Cliente
            </a>
        </div>

        <div class="card card-custom shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="text-dark-custom mb-1 fw-bold">¡Hola, {{ Auth::user()->name ?? 'Administrador' }}! 👋</h4>
                <p class="text-muted mb-0">Aquí tienes las métricas clave de tus clientes en tiempo real.</p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card kpi-card border-l-primary shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small text-uppercase fw-bold">Registrados</p>
                        <h3 class="mb-0 text-dark-custom fw-bold">{{ \App\Models\Cliente::count() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card kpi-card border-l-success shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small text-uppercase fw-bold">Membresías Activas</p>
                        <h3 class="mb-0 text-dark-custom fw-bold">{{ \App\Models\Cliente::where('estado','activa')->count() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card kpi-card border-l-danger shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small text-uppercase fw-bold">Inactivas</p>
                        <h3 class="mb-0 text-dark-custom fw-bold">{{ \App\Models\Cliente::where('estado','inactiva')->count() }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card kpi-card border-l-warning shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1 small text-uppercase fw-bold">Vencen (< 7 días)</p>
                        <h3 class="mb-0 text-dark-custom fw-bold">{{ \App\Models\Cliente::whereDate('vigencia_hasta','<=', now()->addDays(7))->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-white pt-3 pb-2">
                        <h6 class="text-dark-custom fw-bold mb-0">Accesos Rápidos</h6>
                    </div>
                    <div class="card-body d-grid gap-3">
                        <a href="{{ route('clientes.create') }}" class="btn btn-success fw-bold text-start p-3 d-flex justify-content-between align-items-center shadow-sm">
                            <span><i class="bi bi-person-plus me-2"></i> Inscribir Cliente</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-dark fw-bold text-start p-3 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-card-list me-2"></i> Ver Directorio</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-white pt-3 pb-2 d-flex justify-content-between align-items-center">
                        <h6 class="text-dark-custom fw-bold mb-0">Últimos Registros</h6>
                        <a href="{{ route('clientes.index') }}" class="text-success text-decoration-none small fw-bold">Ver todos</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 text-muted">Nombre</th>
                                        <th class="text-muted">Membresía</th>
                                        <th class="text-muted">Vigencia</th>
                                        <th class="text-muted">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(\App\Models\Cliente::orderBy('id','desc')->take(5)->get() as $c)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark-custom">{{ $c->nombre }}</td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($c->membresia) }}</span></td>
                                            <td class="text-muted small">{{ \Carbon\Carbon::parse($c->vigencia_hasta)->format('d/m/Y') }}</td>
                                            <td>
                                                @if($c->estado === 'activa')
                                                    <span class="badge bg-success bg-opacity-25 text-success border border-success">Activa</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">Inactiva</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">Aún no hay clientes registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>