<x-app-layout>
    <style>
        .dashboard-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        .dashboard-card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .kpi-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .quick-action {
            text-decoration: none;
            border-radius: 12px;
            padding: 1rem;
            display: block;
            background: #fff;
            border: 1px solid #dee2e6;
            color: #212529;
            transition: .2s ease;
        }

        .quick-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0,0,0,.08);
            color: #198754;
            border-color: #198754;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .progress {
            height: 8px;
            border-radius: 20px;
        }
    </style>

    <div class="container py-4">

        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">
                    <i class="bi bi-speedometer2 text-success me-2"></i> Dashboard
                </h2>
                <p class="text-muted mb-0">
                    Resumen general del control del gimnasio, membresías, pagos y asistencias.
                </p>
            </div>

            <div class="text-end">
                <span class="badge bg-success px-3 py-2">
                    {{ now()->format('d/m/Y') }}
                </span>
                <div class="small text-muted mt-1">
                    Usuario: {{ auth()->user()->name }} | Rol: {{ auth()->user()->role }}
                </div>
            </div>
        </div>

        @if($clientesVencidos > 0 || $clientesPorVencer->count() > 0 || $clientesInactivos > 0)
            <div class="alert alert-warning shadow-sm mb-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h6 class="fw-bold mb-1">
                            <i class="bi bi-bell-fill me-2"></i>
                            Alertas importantes del gimnasio
                        </h6>
                        <div class="small">
                            Revisa los clientes vencidos, próximos a vencer o inactivos para evitar problemas en recepción.
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        @if($clientesVencidos > 0)
                            <span class="badge bg-danger px-3 py-2">
                                {{ $clientesVencidos }} vencidos
                            </span>
                        @endif

                        @if($clientesPorVencer->count() > 0)
                            <span class="badge bg-warning text-dark px-3 py-2">
                                {{ $clientesPorVencer->count() }} por vencer
                            </span>
                        @endif

                        @if($clientesInactivos > 0)
                            <span class="badge bg-secondary px-3 py-2">
                                {{ $clientesInactivos }} inactivos
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- KPIs principales --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Clientes activos</span>
                            <h3 class="fw-bold text-dark mb-0">{{ $clientesActivos }}</h3>
                            <small class="text-muted">Total clientes: {{ $totalClientes }}</small>
                        </div>
                        <div class="kpi-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Membresías vencidas</span>
                            <h3 class="fw-bold text-danger mb-0">{{ $clientesVencidos }}</h3>
                            <small class="text-muted">Requieren renovación</small>
                        </div>
                        <div class="kpi-icon bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Ingresos de hoy</span>
                            <h3 class="fw-bold text-success mb-0">
                                ${{ number_format($ingresosHoy, 2) }}
                            </h3>
                            <small class="text-muted">{{ $totalPagosHoy }} tickets registrados</small>
                        </div>
                        <div class="kpi-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="dashboard-card p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Asistencias hoy</span>
                            <h3 class="fw-bold text-dark mb-0">{{ $asistenciasHoy }}</h3>
                            <small class="text-muted">
                                {{ $accesosPermitidos }} permitidas / {{ $accesosDenegados }} denegadas
                            </small>
                        </div>
                        <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-door-open-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accesos rápidos --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <a href="{{ route('clientes.index') }}" class="quick-action">
                    <div class="fw-bold">
                        <i class="bi bi-person-plus-fill text-success me-2"></i> Registrar cliente
                    </div>
                    <small class="text-muted">Alta rápida de miembros.</small>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('asistencias.index') }}" class="quick-action">
                    <div class="fw-bold">
                        <i class="bi bi-door-open-fill text-success me-2"></i> Check-in
                    </div>
                    <small class="text-muted">Validar acceso al gimnasio.</small>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('pagos.index') }}" class="quick-action">
                    <div class="fw-bold">
                        <i class="bi bi-receipt-cutoff text-success me-2"></i> Registrar pago
                    </div>
                    <small class="text-muted">Renovar membresía o cobrar.</small>
                </a>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'gerente']))
                <div class="col-md-3">
                    <a href="{{ route('membresias.index') }}" class="quick-action">
                        <div class="fw-bold">
                            <i class="bi bi-card-checklist text-success me-2"></i> Membresías
                        </div>
                        <small class="text-muted">Gestionar planes y precios.</small>
                    </a>
                </div>
            @endif
        </div>

        <div class="row g-4">

            {{-- Caja del día --}}
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-wallet2 text-success me-2"></i> Corte rápido del día
                        </h6>
                    </div>

                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Efectivo</span>
                            <strong class="text-success">${{ number_format($ingresosEfectivo, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tarjeta</span>
                            <strong class="text-primary">${{ number_format($ingresosTarjeta, 2) }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Transferencia</span>
                            <strong class="text-info">${{ number_format($ingresosTransferencia, 2) }}</strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total hoy</span>
                            <strong class="text-success fs-5">${{ number_format($ingresosHoy, 2) }}</strong>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded">
                            <small class="text-muted d-block">Ingresos del mes</small>
                            <h4 class="fw-bold text-dark mb-0">${{ number_format($ingresosMes, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Estado de clientes --}}
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-people-fill text-success me-2"></i> Estado de clientes
                        </h6>
                    </div>

                    <div class="p-3">
                        @php
                            $baseClientes = max($totalClientes, 1);
                            $porcentajeActivos = round(($clientesActivos / $baseClientes) * 100);
                            $porcentajeVencidos = round(($clientesVencidos / $baseClientes) * 100);
                            $porcentajeInactivos = round(($clientesInactivos / $baseClientes) * 100);
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="small fw-bold">Activos</span>
                                <span class="small">{{ $porcentajeActivos }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ $porcentajeActivos }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="small fw-bold">Vencidos</span>
                                <span class="small">{{ $porcentajeVencidos }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" style="width: {{ $porcentajeVencidos }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between">
                                <span class="small fw-bold">Inactivos</span>
                                <span class="small">{{ $porcentajeInactivos }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-secondary" style="width: {{ $porcentajeInactivos }}%"></div>
                            </div>
                        </div>

                        <hr>

                        <div class="row text-center">
                            <div class="col">
                                <h5 class="fw-bold mb-0 text-success">{{ $clientesActivos }}</h5>
                                <small class="text-muted">Activos</small>
                            </div>
                            <div class="col">
                                <h5 class="fw-bold mb-0 text-danger">{{ $clientesVencidos }}</h5>
                                <small class="text-muted">Vencidos</small>
                            </div>
                            <div class="col">
                                <h5 class="fw-bold mb-0 text-secondary">{{ $clientesInactivos }}</h5>
                                <small class="text-muted">Inactivos</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Membresías --}}
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-card-checklist text-success me-2"></i> Planes de membresía
                        </h6>
                    </div>

                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Planes activos</span>
                            <strong>{{ $membresiasActivas }} / {{ $totalMembresias }}</strong>
                        </div>

                        @forelse($membresiasResumen as $membresia)
                            <div class="border rounded p-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $membresia->nombre }}</strong>
                                    <span class="text-success fw-bold">
                                        ${{ number_format($membresia->precio, 2) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ $membresia->clientes_count }} clientes | {{ $membresia->duracion_dias }} días
                                </small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                No hay membresías registradas.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4 mt-1">

            {{-- Próximos a vencer --}}
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-calendar-x text-warning me-2"></i> Próximos a vencer
                        </h6>
                    </div>

                    <div class="p-3">
                        @forelse($clientesPorVencer as $cliente)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <strong>{{ $cliente->nombre }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $cliente->nombre_membresia }}</small>
                                </div>
                                <span class="badge bg-warning text-dark">
                                    {{ $cliente->vigencia_hasta->format('d/m/Y') }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                No hay membresías por vencer en los próximos 7 días.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Últimos pagos --}}
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-receipt text-success me-2"></i> Últimos pagos
                        </h6>
                    </div>

                    <div class="p-3">
                        @forelse($ultimosPagos as $pago)
                            <div class="border-bottom py-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $pago->cliente?->nombre ?? 'Cliente eliminado' }}</strong>
                                    <span class="text-success fw-bold">
                                        ${{ number_format($pago->monto, 2) }}
                                    </span>
                                </div>
                                <small class="text-muted">
                                    {{ $pago->concepto }} | {{ $pago->metodo_pago }} |
                                    {{ ($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y h:i A') }}
                                </small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                No hay pagos registrados.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Últimas asistencias --}}
            <div class="col-lg-4">
                <div class="dashboard-card h-100">
                    <div class="dashboard-card-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-door-open text-primary me-2"></i> Últimas asistencias
                        </h6>
                    </div>

                    <div class="p-3">
                        @forelse($ultimasAsistencias as $asistencia)
                            @php
                                $fechaEntrada = $asistencia->fecha_hora ?? $asistencia->created_at;
                            @endphp

                            <div class="border-bottom py-2">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $asistencia->cliente?->nombre ?? 'Cliente eliminado' }}</strong>

                                    @if($asistencia->resultado === 'permitido')
                                        <span class="badge bg-success">Permitido</span>
                                    @else
                                        <span class="badge bg-danger">Denegado</span>
                                    @endif
                                </div>

                                <small class="text-muted">
                                    {{ $asistencia->motivo ?? 'Sin detalle' }} |
                                    {{ $fechaEntrada->format('d/m/Y h:i A') }}
                                </small>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                No hay asistencias registradas.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>