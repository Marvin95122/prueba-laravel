<x-app-layout>
    <style>
        .card-custom { background-color: white; border: 1px solid #dee2e6; border-radius: 10px; }
        .bg-header-custom { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; border-top-left-radius: 10px; border-top-right-radius: 10px; }
    </style>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark fw-bold"><i class="bi bi-people-fill text-success me-2"></i> Directorio de Clientes</h2>
                <small class="text-muted">Administra los miembros del gimnasio, sus membresías y estado de acceso.</small>
            </div>
        </div>

        @if(session('success') || session('ok'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') ?? session('ok') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card card-custom shadow-sm h-100 border-top border-success border-4">
                    <div class="card-header bg-header-custom py-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-person-plus-fill text-success me-2"></i> Registrar Cliente</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('clientes.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre Completo</label>
                                <input type="text" name="nombre" class="form-control" required placeholder="Ej. Juan Pérez" value="{{ old('nombre') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" name="telefono" class="form-control" placeholder="Ej. 951 123 4567" value="{{ old('telefono') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Membresía</label>
                                <select name="membresia" class="form-select" required>
                                    <option value="basica">Básica</option>
                                    <option value="plus">Plus</option>
                                    <option value="premium">Premium VIP</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Vigencia hasta</label>
                                <input type="date" name="vigencia_hasta" class="form-control" required value="{{ old('vigencia_hasta') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="activa">Activa (Permitir acceso)</option>
                                    <option value="inactiva">Inactiva (Bloquear acceso)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm">
                                <i class="bi bi-save-fill me-1"></i> Guardar Cliente
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-header-custom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-card-list text-success me-2"></i> Listado General</h6>
                        <span class="badge bg-success">{{ $clientes->total() ?? 0 }} registrados</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nombre</th>
                                        <th>Contacto</th>
                                        <th>Membresía</th>
                                        <th>Estado</th>
                                        <th class="text-end pe-4">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clientes as $c)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">{{ $c->nombre }}</td>
                                            <td class="text-muted small"><i class="bi bi-telephone-fill me-1"></i> {{ $c->telefono ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-secondary mb-1 d-block w-75">{{ ucfirst($c->membresia) }}</span>
                                                <small class="text-muted" style="font-size: 0.75rem;">Vence: {{ \Carbon\Carbon::parse($c->vigencia_hasta)->format('d/m/Y') }}</small>
                                            </td>
                                            <td>
                                                @if($c->estado === 'activa')
                                                    <span class="badge bg-success bg-opacity-25 text-success border border-success">Activa</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">Inactiva</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->role === 'admin')
                                                        <a href="{{ route('clientes.edit', $c) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                        <form action="{{ route('clientes.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $c->nombre }}?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled title="Solo Admin"><i class="bi bi-lock-fill"></i></button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                                No hay clientes registrados en la base de datos.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-3">
                        {{ $clientes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>