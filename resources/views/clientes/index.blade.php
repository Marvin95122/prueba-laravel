<x-app-layout>
    <style>
        .client-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        .client-card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .form-panel {
            border-top: 4px solid #198754;
        }

        .filter-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        .client-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #eaf7ee;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
        }

        .membership-pill {
            display: inline-block;
            min-width: 120px;
            text-align: center;
            border-radius: 20px;
            padding: .35rem .75rem;
            font-size: .78rem;
            font-weight: 700;
            background: #6c757d;
            color: white;
        }

        .status-pill {
            border-radius: 20px;
            padding: .35rem .75rem;
            font-size: .78rem;
            font-weight: 700;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .quick-summary {
            background: #eaf7ee;
            border: 1px dashed #198754;
            border-radius: 12px;
        }

        .btn-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: .75rem;
        }
    </style>

    <div class="container py-4">

        {{-- Encabezado --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark fw-bold">
                    <i class="bi bi-people-fill text-success me-2"></i>
                    Directorio de Clientes
                </h2>
                <small class="text-muted">
                    Administra los miembros del gimnasio, sus membresías y estado de acceso.
                </small>
            </div>

            <div class="quick-summary px-4 py-2 text-center">
                <span class="d-block text-success fw-bold small text-uppercase">
                    Clientes registrados
                </span>
                <span class="fs-4 fw-bold text-dark">
                    {{ $clientes->total() ?? 0 }}
                </span>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success') || session('ok'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') ?? session('ok') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
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

        <div class="row g-4">

            {{-- Formulario lateral --}}
            <div class="col-lg-4">
                <div class="client-card form-panel h-100">
                    <div class="client-card-header py-3 px-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-person-plus-fill text-success me-2"></i>
                            Registrar Cliente
                        </h6>
                    </div>

                    <div class="p-3">
                        <form action="{{ route('clientes.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre completo</label>
                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control"
                                    required
                                    placeholder="Ej. Juan Pérez"
                                    value="{{ old('nombre') }}"
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input
                                    type="text"
                                    name="telefono"
                                    class="form-control"
                                    placeholder="Ej. 951 123 4567"
                                    value="{{ old('telefono') }}"
                                >
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Membresía</label>
                                <select name="membresia_id" id="membresia_id" class="form-select" required>
                                    <option value="">Selecciona una membresía</option>

                                    @foreach($membresias as $membresia)
                                        <option
                                            value="{{ $membresia->id }}"
                                            data-duracion="{{ $membresia->duracion_dias }}"
                                            data-precio="{{ $membresia->precio }}"
                                            @selected(old('membresia_id') == $membresia->id)
                                        >
                                            {{ $membresia->nombre }}
                                            - ${{ number_format($membresia->precio, 2) }}
                                            / {{ $membresia->duracion_dias }} días
                                        </option>
                                    @endforeach
                                </select>

                                <small class="text-muted">
                                    Al seleccionar un plan se puede calcular la vigencia automáticamente.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Vigencia hasta</label>
                                <input
                                    type="date"
                                    name="vigencia_hasta"
                                    id="vigencia_hasta"
                                    class="form-control"
                                    required
                                    value="{{ old('vigencia_hasta') }}"
                                >
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="activa" @selected(old('estado') === 'activa')>
                                        Activa - Permitir acceso
                                    </option>
                                    <option value="inactiva" @selected(old('estado') === 'inactiva')>
                                        Inactiva - Bloquear acceso
                                    </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-save-fill me-1"></i>
                                Guardar Cliente
                            </button>
                        </form>

                        <div class="mt-4 p-3 bg-light border rounded">
                            <small class="text-muted d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Los clientes activos con membresía vigente podrán registrar entrada en el módulo de asistencias.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Listado y filtros --}}
            <div class="col-lg-8">

                {{-- Filtros --}}
                <div class="filter-card mb-4">
                    <div class="client-card-header py-3 px-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-funnel-fill text-success me-2"></i>
                            Filtros de búsqueda
                        </h6>
                    </div>

                    <div class="p-3">
                        <form method="GET" action="{{ route('clientes.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Buscar</label>
                                <input
                                    type="text"
                                    name="buscar"
                                    class="form-control"
                                    value="{{ request('buscar') }}"
                                    placeholder="Nombre o teléfono"
                                >
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Estado</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="activa" @selected(request('estado') === 'activa')>
                                        Activa
                                    </option>
                                    <option value="inactiva" @selected(request('estado') === 'inactiva')>
                                        Inactiva
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label small fw-bold">Membresía</label>
                                <select name="membresia_id" class="form-select">
                                    <option value="">Todas</option>
                                    @foreach($membresias as $membresia)
                                        <option value="{{ $membresia->id }}" @selected(request('membresia_id') == $membresia->id)>
                                            {{ $membresia->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Vigencia</label>
                                <select name="vigencia" class="form-select">
                                    <option value="">Todas</option>
                                    <option value="vigente" @selected(request('vigencia') === 'vigente')>
                                        Vigente
                                    </option>
                                    <option value="vencida" @selected(request('vigencia') === 'vencida')>
                                        Vencida
                                    </option>
                                    <option value="por_vencer" @selected(request('vigencia') === 'por_vencer')>
                                        Por vencer
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button class="btn btn-success w-100 fw-bold" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>

                                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary w-100">
                                    Limpiar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="client-card">
                    <div class="client-card-header py-3 px-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-card-list text-success me-2"></i>
                                Listado General
                            </h6>
                            <small class="text-muted">
                                Mostrando {{ $clientes->count() }} de {{ $clientes->total() ?? 0 }} clientes.
                            </small>
                        </div>

                        <span class="badge bg-success px-3 py-2">
                            {{ $clientes->total() ?? 0 }} registrados
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Cliente</th>
                                    <th>Contacto</th>
                                    <th>Membresía</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-3">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($clientes as $c)
                                    @php
                                        $vigencia = $c->vigencia_hasta ? \Carbon\Carbon::parse($c->vigencia_hasta) : null;
                                        $diasRestantes = $vigencia ? today()->diffInDays($vigencia, false) : null;

                                        $estadoVigencia = 'Sin vigencia';
                                        $claseVigencia = 'bg-secondary';

                                        if ($vigencia) {
                                            if ($diasRestantes < 0) {
                                                $estadoVigencia = 'Vencida';
                                                $claseVigencia = 'bg-danger';
                                            } elseif ($diasRestantes <= 7) {
                                                $estadoVigencia = 'Por vencer';
                                                $claseVigencia = 'bg-warning text-dark';
                                            } else {
                                                $estadoVigencia = 'Vigente';
                                                $claseVigencia = 'bg-success';
                                            }
                                        }
                                    @endphp

                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="client-avatar">
                                                    {{ strtoupper(substr($c->nombre, 0, 1)) }}
                                                </div>

                                                <div>
                                                    <div class="fw-bold text-dark">
                                                        {{ $c->nombre }}
                                                    </div>
                                                    <small class="text-muted">
                                                        ID: #{{ $c->id }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="text-muted small">
                                                <i class="bi bi-telephone-fill me-1"></i>
                                                {{ $c->telefono ?? 'Sin teléfono' }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="membership-pill">
                                                {{ $c->nombre_membresia }}
                                            </span>

                                            @if($c->membresiaPlan)
                                                <br>
                                                <small class="text-muted">
                                                    ${{ number_format($c->membresiaPlan->precio, 2) }}
                                                    / {{ $c->membresiaPlan->duracion_dias }} días
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            @if($vigencia)
                                                <div class="fw-semibold">
                                                    {{ $vigencia->format('d/m/Y') }}
                                                </div>

                                                <span class="badge {{ $claseVigencia }}">
                                                    {{ $estadoVigencia }}
                                                </span>

                                                @if($diasRestantes !== null)
                                                    <br>
                                                    <small class="text-muted">
                                                        @if($diasRestantes < 0)
                                                            Hace {{ abs($diasRestantes) }} días
                                                        @elseif($diasRestantes === 0)
                                                            Vence hoy
                                                        @else
                                                            Faltan {{ $diasRestantes }} días
                                                        @endif
                                                    </small>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">
                                                    Sin fecha
                                                </span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($c->estado === 'activa')
                                                <span class="status-pill bg-success bg-opacity-25 text-success border border-success">
                                                    <i class="bi bi-check-circle-fill me-1"></i>
                                                    Activa
                                                </span>
                                            @else
                                                <span class="status-pill bg-danger bg-opacity-25 text-danger border border-danger">
                                                    <i class="bi bi-x-circle-fill me-1"></i>
                                                    Inactiva
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-end pe-3">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('clientes.show', $c) }}"
                                                   class="btn btn-sm btn-outline-secondary btn-icon"
                                                   title="Ver perfil">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>

                                                @if(in_array(auth()->user()->role, ['admin', 'gerente']))
                                                    <a href="{{ route('clientes.edit', $c) }}"
                                                       class="btn btn-sm btn-outline-primary btn-icon"
                                                       title="Editar">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                @endif

                                                @if(auth()->user()->role === 'admin')
                                                    <form action="{{ route('clientes.destroy', $c) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar a {{ $c->nombre }}?');">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger btn-icon"
                                                                title="Eliminar">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(auth()->user()->role === 'recepcion')
                                                    <button class="btn btn-sm btn-secondary btn-icon"
                                                            disabled
                                                            title="Recepción solo puede registrar y consultar">
                                                        <i class="bi bi-lock-fill"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox text-secondary"></i>
                                                <h6 class="fw-bold">No hay clientes para mostrar</h6>
                                                <p class="mb-0">
                                                    No se encontraron clientes registrados o no hay resultados con los filtros aplicados.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-white border-top p-3">
                        {{ $clientes->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const membresiaSelect = document.getElementById('membresia_id');
            const vigenciaInput = document.getElementById('vigencia_hasta');

            function calcularVigencia() {
                const option = membresiaSelect.options[membresiaSelect.selectedIndex];

                if (!option || !option.value || vigenciaInput.value) {
                    return;
                }

                const duracion = parseInt(option.dataset.duracion || 0);

                if (!duracion) {
                    return;
                }

                const fecha = new Date();
                fecha.setDate(fecha.getDate() + duracion);

                const year = fecha.getFullYear();
                const month = String(fecha.getMonth() + 1).padStart(2, '0');
                const day = String(fecha.getDate()).padStart(2, '0');

                vigenciaInput.value = `${year}-${month}-${day}`;
            }

            membresiaSelect.addEventListener('change', function () {
                if (!vigenciaInput.value) {
                    calcularVigencia();
                }
            });
        });
    </script>
</x-app-layout>