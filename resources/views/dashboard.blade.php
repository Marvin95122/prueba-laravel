<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h5 mb-0 text-white"><i class="bi bi-grid-1x2-fill text-success me-2"></i> Panel de Control</h2>
                <small class="text-muted">Resumen general de operaciones del gimnasio.</small>
            </div>
            <a href="{{ route('clientes.create') }}" class="btn btn-success btn-sm fw-bold">
                <i class="bi bi-person-plus-fill"></i> Nuevo Cliente
            </a>
        </div>
    </x-slot>

    <div class="container">
        
        <div class="card card-dark mb-4 shadow-sm border-0">
            <div class="card-body py-4">
                <h4 class="text-white mb-1">¡Hola, {{ Auth::user()->name ?? 'Administrador' }}! 👋</h4>
                <p class="text-muted mb-0">Aquí tienes las métricas clave de tus clientes en tiempo real.</p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card card-dark h-100 shadow-sm border-start border-primary border-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary bg-opacity-25 p-3 rounded text-primary me-3">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Registrados</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ \App\Models\Cliente::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-dark h-100 shadow-sm border-start border-success border-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success bg-opacity-25 p-3 rounded text-success me-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Activas</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ \App\Models\Cliente::where('estado','activa')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-dark h-100 shadow-sm border-start border-danger border-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-danger bg-opacity-25 p-3 rounded text-danger me-3">
                            <i class="bi bi-x-circle fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Inactivas</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ \App\Models\Cliente::where('estado','inactiva')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card card-dark h-100 shadow-sm border-start border-warning border-4">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning bg-opacity-25 p-3 rounded text-warning me-3">
                            <i class="bi bi-clock-history fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 small text-uppercase fw-bold">Vencen (< 7 días)</h6>
                            <h3 class="mb-0 text-white fw-bold">{{ \App\Models\Cliente::whereDate('vigencia_hasta','<=', now()->addDays(7))->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card card-dark shadow-sm h-100">
                    <div class="card-header border-secondary bg-transparent pt-3 pb-2">
                        <h6 class="text-white fw-bold mb-0">Accesos Rápidos</h6>
                    </div>
                    <div class="card-body d-grid gap-3">
                        <a href="{{ route('clientes.create') }}" class="btn btn-success fw-bold text-start p-3 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-person-plus me-2"></i> Inscribir Cliente</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-light text-start p-3 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-card-list me-2"></i> Ver Directorio</span>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-dark shadow-sm h-100">
                    <div class="card-header border-secondary bg-transparent pt-3 pb-2 d-flex justify-content-between align-items-center">
                        <h6 class="text-white fw-bold mb-0">Últimos Registros</h6>
                        <a href="{{ route('clientes.index') }}" class="text-success text-decoration-none small">Ver todos</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-dark table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Nombre</th>
                                        <th>Membresía</th>
                                        <th>Vigencia</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="border-top-0">
                                    @forelse(\App\Models\Cliente::orderBy('id','desc')->take(5)->get() as $c)
                                        <tr>
                                            <td class="ps-4 text-white fw-semibold">{{ $c->nombre }}</td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($c->membresia) }}</span></td>
                                            <td class="text-muted">{{ \Carbon\Carbon::parse($c->vigencia_hasta)->format('d/m/Y') }}</td>
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
                                            <td colspan="4" class="text-center py-4 text-muted">Aún no hay clientes registrados en la base de datos.</td>
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