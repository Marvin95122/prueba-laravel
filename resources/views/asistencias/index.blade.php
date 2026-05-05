<x-app-layout>
    <style>
        .card-custom {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
        }

        .bg-header-custom {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .mini-kpi {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
        }

        .access-box {
            background: #eaf7ee;
            border: 2px dashed #1f7c34;
            border-radius: 12px;
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark fw-bold">
                    <i class="bi bi-person-check-fill text-success me-2"></i> Control de Asistencias
                </h2>
                <small class="text-muted">
                    Registro de entrada con validación automática de membresía activa y vigente.
                </small>
            </div>

            <div class="access-box px-4 py-2 text-center shadow-sm">
                <span class="d-block text-success fw-bold small text-uppercase">Entradas Hoy</span>
                <span class="fs-4 fw-bold text-dark">{{ $totalHoy }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger shadow-sm">
                <strong>Revisa los siguientes errores:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Accesos permitidos</span>
                    <h4 class="fw-bold mb-0 text-success">{{ $permitidas }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Accesos denegados</span>
                    <h4 class="fw-bold mb-0 text-danger">{{ $denegadas }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Clientes vencidos</span>
                    <h4 class="fw-bold mb-0 text-warning">{{ $clientesVencidos }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Usuario en recepción</span>
                    <h5 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h5>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- Formulario --}}
            <div class="col-lg-4">
                <div class="card card-custom shadow-sm border-top border-success border-4 h-100">
                    <div class="card-header bg-header-custom py-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-door-open-fill text-success me-2"></i> Registrar Entrada
                        </h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('asistencias.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select form-select-lg shadow-sm" required>
                                    <option value="" disabled selected>-- Selecciona un cliente --</option>

                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                                data-nombre="{{ $cliente->nombre }}"
                                                data-membresia="{{ $cliente->nombre_membresia }}"
                                                data-estado="{{ $cliente->estado }}"
                                                data-vigencia="{{ $cliente->vigencia_hasta ? $cliente->vigencia_hasta->format('d/m/Y') : 'Sin fecha' }}">
                                            {{ $cliente->nombre }} - {{ $cliente->nombre_membresia }}
                                            @if($cliente->vigencia_hasta)
                                                (vence {{ $cliente->vigencia_hasta->format('d/m/Y') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="ticket-box border rounded p-3 mb-4 bg-light" id="previewCliente">
                                <small class="text-muted d-block">Vista previa del cliente</small>
                                <h5 class="fw-bold mb-1" id="previewNombre">Selecciona un cliente</h5>
                                <div class="small text-muted">
                                    Membresía: <span id="previewMembresia">---</span>
                                </div>
                                <div class="small text-muted">
                                    Vigencia: <span id="previewVigencia">---</span>
                                </div>
                                <div class="mt-2">
                                    Estado:
                                    <span class="badge bg-secondary" id="previewEstado">---</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold py-3 shadow-sm">
                                <i class="bi bi-door-open-fill me-2"></i> VALIDAR Y DAR ACCESO
                            </button>
                        </form>

                        <div class="mt-4 small text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            El sistema valida automáticamente si la membresía está activa y vigente.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="col-lg-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-header-custom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-clock-history text-success me-2"></i>
                            Entradas de Hoy ({{ now()->format('d/m/Y') }})
                        </h6>
                        <span class="badge bg-success">{{ $asistencias->count() }} registros</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Hora</th>
                                        <th>Cliente</th>
                                        <th>Membresía</th>
                                        <th>Resultado</th>
                                        <th>Motivo</th>
                                        <th>Registró</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($asistencias as $a)
                                        @php
                                            $fechaRegistro = $a->fecha_hora ?? $a->created_at;
                                        @endphp

                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">
                                                {{ $fechaRegistro->format('h:i A') }}
                                            </td>

                                            <td class="fw-bold text-dark">
                                                {{ $a->cliente?->nombre ?? 'Cliente eliminado' }}
                                            </td>

                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $a->cliente?->nombre_membresia ?? 'Sin membresía' }}
                                                </span>

                                                @if($a->cliente?->vigencia_hasta)
                                                    <br>
                                                    <small class="text-muted">
                                                        Vence: {{ $a->cliente->vigencia_hasta->format('d/m/Y') }}
                                                    </small>
                                                @endif
                                            </td>

                                            <td>
                                                @if($a->resultado === 'permitido')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle"></i> Permitido
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle"></i> Denegado
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <small class="{{ $a->resultado === 'permitido' ? 'text-success' : 'text-danger' }}">
                                                    {{ $a->motivo ?? 'Sin detalle' }}
                                                </small>
                                            </td>

                                            <td>
                                                <small class="text-muted">
                                                    {{ $a->user?->name ?? 'Sistema' }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                Aún no hay ingresos registrados hoy.
                                            </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectCliente = document.getElementById('cliente_id');

            const previewNombre = document.getElementById('previewNombre');
            const previewMembresia = document.getElementById('previewMembresia');
            const previewVigencia = document.getElementById('previewVigencia');
            const previewEstado = document.getElementById('previewEstado');

            function actualizarPreview() {
                const option = selectCliente.options[selectCliente.selectedIndex];

                if (!option || !option.value) {
                    previewNombre.textContent = 'Selecciona un cliente';
                    previewMembresia.textContent = '---';
                    previewVigencia.textContent = '---';
                    previewEstado.textContent = '---';
                    previewEstado.className = 'badge bg-secondary';
                    return;
                }

                const nombre = option.dataset.nombre;
                const membresia = option.dataset.membresia;
                const vigencia = option.dataset.vigencia;
                const estado = option.dataset.estado;

                previewNombre.textContent = nombre;
                previewMembresia.textContent = membresia;
                previewVigencia.textContent = vigencia;
                previewEstado.textContent = estado;

                if (estado === 'activa') {
                    previewEstado.className = 'badge bg-success';
                } else {
                    previewEstado.className = 'badge bg-danger';
                }
            }

            selectCliente.addEventListener('change', actualizarPreview);
            actualizarPreview();
        });
    </script>
</x-app-layout>