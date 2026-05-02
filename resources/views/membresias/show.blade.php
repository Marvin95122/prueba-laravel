<x-app-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 fw-bold text-dark">
                    <i class="bi bi-card-checklist text-success me-2"></i> Detalle de membresía
                </h2>
                <small class="text-muted">Información completa del plan.</small>
            </div>

            <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary">
                Volver
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="fw-bold text-dark mb-1">{{ $membresia->nombre }}</h3>

                @if($membresia->estado === 'activa')
                    <span class="badge bg-success mb-3">Activa</span>
                @else
                    <span class="badge bg-danger mb-3">Inactiva</span>
                @endif

                <div class="row g-4 mt-2">
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Precio</small>
                            <h4 class="fw-bold text-success mb-0">${{ number_format($membresia->precio, 2) }}</h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Duración</small>
                            <h4 class="fw-bold text-dark mb-0">{{ $membresia->duracion_dias }} días</h4>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light">
                            <small class="text-muted">Clientes asociados</small>
                            <h4 class="fw-bold text-dark mb-0">{{ $membresia->clientes()->count() }}</h4>
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold">Descripción</h6>
                <p class="text-muted">{{ $membresia->descripcion ?? 'Sin descripción.' }}</p>

                <h6 class="fw-bold">Beneficios</h6>
                <p class="text-muted">{{ $membresia->beneficios ?? 'Sin beneficios registrados.' }}</p>

                <div class="mt-4">
                    <a href="{{ route('membresias.edit', $membresia) }}" class="btn btn-primary">
                        Editar
                    </a>
                    <a href="{{ route('membresias.index') }}" class="btn btn-outline-secondary">
                        Volver
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>