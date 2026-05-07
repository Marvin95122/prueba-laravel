<x-app-layout>
    <style>
        .profile-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        .profile-header {
            background: linear-gradient(135deg, #0f5132, #198754);
            color: white;
            border-radius: 14px;
            padding: 1.5rem;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 1rem;
            height: 100%;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .avatar-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
        }
    </style>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 fw-bold mb-0 text-dark">
                    <i class="bi bi-person-vcard text-success me-2"></i> Perfil del Cliente
                </h2>
                <small class="text-muted">
                    Consulta datos generales, membresía, pagos y asistencias del cliente.
                </small>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'gerente']))
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                @endif
            </div>
        </div>

        {{-- Encabezado del perfil --}}
        <div class="profile-header shadow-sm mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                    </div>

                    <div>
                        <h3 class="fw-bold mb-1">{{ $cliente->nombre }}</h3>
                        <div class="small">
                            <i class="bi bi-telephone"></i>
                            {{ $cliente->telefono ?? 'Sin teléfono registrado' }}
                        </div>
                        <div class="small">
                            <i class="bi bi-card-checklist"></i>
                            {{ $cliente->nombre_membresia }}
                        </div>
                    </div>
                </div>

                <div class="text-md-end">
                    @if($cliente->estado === 'activa')
                        <span class="badge bg-light text-success px-3 py-2 mb-2">
                            Cliente activo
                        </span>
                    @else
                        <span class="badge bg-danger px-3 py-2 mb-2">
                            Cliente inactivo
                        </span>
                    @endif

                    <div>
                        @if($cliente->vigencia_hasta)
                            <strong>Vence:</strong> {{ $cliente->vigencia_hasta->format('d/m/Y') }}
                        @else
                            <strong>Vence:</strong> Sin fecha
                        @endif
                    </div>

                    <div class="small">
                        @if($diasRestantes === null)
                            Sin vigencia registrada.
                        @elseif($diasRestantes < 0)
                            <span class="text-warning fw-bold">Membresía vencida hace {{ abs($diasRestantes) }} días.</span>
                        @elseif($diasRestantes === 0)
                            <span class="text-warning fw-bold">La membresía vence hoy.</span>
                        @else
                            <span class="text-light">Quedan {{ $diasRestantes }} días de vigencia.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Métricas --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="metric-card shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Total pagado</span>
                    <h4 class="fw-bold text-success mb-0">
                        ${{ number_format($totalPagado, 2) }}
                    </h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Pagos registrados</span>
                    <h4 class="fw-bold text-dark mb-0">{{ $totalPagos }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Asistencias</span>
                    <h4 class="fw-bold text-primary mb-0">{{ $totalAsistencias }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Accesos</span>
                    <h4 class="fw-bold mb-0">
                        <span class="text-success">{{ $accesosPermitidos }}</span>
                        /
                        <span class="text-danger">{{ $accesosDenegados }}</span>
                    </h4>
                    <small class="text-muted">Permitidos / Denegados</small>
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="{{ route('pagos.index') }}" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                    <i class="bi bi-cash-coin me-2"></i> Registrar pago / Renovar
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('asistencias.index') }}" class="btn btn-outline-success w-100 py-3 fw-bold shadow-sm">
                    <i class="bi bi-door-open-fill me-2"></i> Registrar asistencia
                </a>
            </div>

            <div class="col-md-4">
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary w-100 py-3 fw-bold shadow-sm">
                    <i class="bi bi-people-fill me-2"></i> Ver directorio
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- Información de membresía --}}
            <div class="col-lg-4">
                <div class="profile-card h-100">
                    <div class="p-3 border-bottom bg-light rounded-top">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-card-checklist text-success me-2"></i> Membresía actual
                        </h6>
                    </div>

                    <div class="p-3">
                        <div class="mb-3">
                            <small class="text-muted d-block">Plan</small>
                            <h5 class="fw-bold mb-0">{{ $cliente->nombre_membresia }}</h5>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Estado</small>
                            @if($cliente->estado === 'activa' && !$cliente->membresia_vencida)
                                <span class="badge bg-success">Activa y vigente</span>
                            @elseif($cliente->membresia_vencida)
                                <span class="badge bg-danger">Vencida</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Fecha de vencimiento</small>
                            <strong>
                                {{ $cliente->vigencia_hasta ? $cliente->vigencia_hasta->format('d/m/Y') : 'Sin fecha' }}
                            </strong>
                        </div>

                        @if($cliente->membresiaPlan)
                            <hr>

                            <div class="mb-3">
                                <small class="text-muted d-block">Precio del plan</small>
                                <strong class="text-success">
                                    ${{ number_format($cliente->membresiaPlan->precio, 2) }}
                                </strong>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">Duración</small>
                                <strong>{{ $cliente->membresiaPlan->duracion_dias }} días</strong>
                            </div>

                            <div>
                                <small class="text-muted d-block">Beneficios</small>
                                <p class="mb-0 text-muted">
                                    {{ $cliente->membresiaPlan->beneficios ?? 'Sin beneficios registrados.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Historial de pagos --}}
            <div class="col-lg-8">
                <div class="profile-card h-100">
                    <div class="p-3 border-bottom bg-light rounded-top d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-receipt text-success me-2"></i> Historial de pagos
                        </h6>
                        <span class="badge bg-success">{{ $cliente->pagos->count() }} registros</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Folio</th>
                                    <th>Fecha</th>
                                    <th>Concepto</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                    <th class="text-end pe-3">Monto</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($cliente->pagos->take(8) as $pago)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="badge bg-dark">
                                                {{ $pago->folio ?? 'S/F' }}
                                            </span>
                                        </td>

                                        <td>
                                            <small class="text-muted">
                                                {{ ($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y h:i A') }}
                                            </small>
                                        </td>

                                        <td>
                                            <strong>{{ $pago->concepto }}</strong>
                                            @if($pago->membresia)
                                                <br>
                                                <small class="text-muted">Plan: {{ $pago->membresia->nombre }}</small>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $pago->metodo_pago }}
                                            </span>
                                        </td>

                                        <td>
                                            @if($pago->estado === 'pagado')
                                                <span class="badge bg-success">Pagado</span>
                                            @elseif($pago->estado === 'pendiente')
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                            @else
                                                <span class="badge bg-danger">Cancelado</span>
                                            @endif
                                        </td>

                                        <td class="text-end pe-3 fw-bold text-success">
                                            ${{ number_format($pago->monto, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Este cliente aún no tiene pagos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        {{-- Historial de asistencias --}}
        <div class="profile-card mt-4">
            <div class="p-3 border-bottom bg-light rounded-top d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-door-open-fill text-success me-2"></i> Historial de asistencias
                </h6>
                <span class="badge bg-primary">{{ $cliente->asistencias->count() }} registros</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Fecha y hora</th>
                            <th>Resultado</th>
                            <th>Motivo</th>
                            <th>Registró</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($cliente->asistencias->take(10) as $asistencia)
                            @php
                                $fechaEntrada = $asistencia->fecha_hora ?? $asistencia->created_at;
                            @endphp

                            <tr>
                                <td class="ps-3">
                                    <strong>{{ $fechaEntrada->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $fechaEntrada->format('h:i A') }}</small>
                                </td>

                                <td>
                                    @if($asistencia->resultado === 'permitido')
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
                                    <span class="{{ $asistencia->resultado === 'permitido' ? 'text-success' : 'text-danger' }}">
                                        {{ $asistencia->motivo ?? 'Sin detalle' }}
                                    </span>
                                </td>

                                <td>
                                    <small class="text-muted">
                                        {{ $asistencia->user?->name ?? 'Sistema' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Este cliente aún no tiene asistencias registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>