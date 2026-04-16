<x-app-layout>
    <style>
        .card-custom { background-color: white; border: 1px solid #dee2e6; border-radius: 10px; }
        .bg-header-custom { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; border-top-left-radius: 10px; border-top-right-radius: 10px; }
    </style>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark fw-bold"><i class="bi bi-person-check-fill text-success me-2"></i> Control de Asistencias</h2>
                <small class="text-muted">Registro diario de acceso a las instalaciones.</small>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card card-custom shadow-sm border-top border-success border-4">
                    <div class="card-header bg-header-custom py-3">
                        <h6 class="fw-bold mb-0 text-dark">Registrar Entrada</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('asistencias.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Seleccionar Cliente</label>
                                <select name="cliente_id" class="form-select form-select-lg shadow-sm" required>
                                    <option value="" disabled selected>-- Buscar cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold py-3 shadow-sm">
                                <i class="bi bi-door-open-fill me-2"></i> DAR ACCESO
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom shadow-sm">
                    <div class="card-header bg-header-custom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">Entradas de Hoy ({{ date('d/m/Y') }})</h6>
                        <span class="badge bg-success">{{ $asistencias->count() }} personas</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Hora</th>
                                        <th>Cliente</th>
                                        <th>Membresía</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($asistencias as $a)
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">{{ $a->created_at->format('h:i A') }}</td>
                                            <td class="fw-bold text-dark">{{ $a->cliente->nombre }}</td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($a->cliente->membresia) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">Aún no hay ingresos registrados hoy.</td>
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