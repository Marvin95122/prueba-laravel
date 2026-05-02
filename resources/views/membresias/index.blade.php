<x-app-layout>
    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 fw-bold text-dark">
                    <i class="bi bi-card-checklist text-success me-2"></i> Membresías
                </h2>
                <small class="text-muted">Administración de planes disponibles para los clientes.</small>
            </div>

            <a href="{{ route('membresias.create') }}" class="btn btn-success fw-bold shadow-sm">
                <i class="bi bi-plus-circle"></i> Nueva membresía
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-dark">Listado de membresías</h6>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nombre</th>
                                <th>Precio</th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($membresias as $membresia)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $membresia->nombre }}</div>
                                        <small class="text-muted">{{ $membresia->descripcion ?? 'Sin descripción' }}</small>
                                    </td>

                                    <td class="fw-bold text-success">
                                        ${{ number_format($membresia->precio, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $membresia->duracion_dias }} días
                                        </span>
                                    </td>

                                    <td>
                                        @if($membresia->estado === 'activa')
                                            <span class="badge bg-success bg-opacity-25 text-success border border-success">
                                                Activa
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="{{ route('membresias.show', $membresia) }}" class="btn btn-sm btn-outline-secondary">
                                            Ver
                                        </a>

                                        <a href="{{ route('membresias.edit', $membresia) }}" class="btn btn-sm btn-outline-primary">
                                            Editar
                                        </a>

                                        @if(auth()->user()->role === 'admin')
                                            <form action="{{ route('membresias.destroy', $membresia) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar esta membresía?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No hay membresías registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $membresias->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>