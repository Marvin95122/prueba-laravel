<x-app-layout>
    <style>
        .report-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,.04);
        }

        .report-header {
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .kpi-card {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            padding: 1rem;
            height: 100%;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>

    <div class="container py-4">

        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 fw-bold text-dark mb-0">
                    <i class="bi bi-bar-chart-fill text-success me-2"></i> Reportes
                </h2>
                <small class="text-muted">
                    Consulta ingresos, asistencias y estado de membresías por rango de fechas.
                </small>
            </div>

            <span class="badge bg-success px-3 py-2">
                {{ $fechaInicio->format('d/m/Y') }} - {{ $fechaFin->format('d/m/Y') }}
            </span>
        </div>

        {{-- Filtros --}}
        <div class="report-card mb-4">
            <div class="report-header p-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-funnel-fill text-success me-2"></i> Filtros de búsqueda
                </h6>
            </div>

            <div class="p-3">
                <form method="GET" action="{{ route('reportes.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" class="form-control"
                               value="{{ request('fecha_inicio', $fechaInicio->toDateString()) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Fecha fin</label>
                        <input type="date" name="fecha_fin" class="form-control"
                               value="{{ request('fecha_fin', $fechaFin->toDateString()) }}">
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success fw-bold w-100">
                            <i class="bi bi-search"></i> Consultar
                        </button>

                        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary w-100">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- KPIs --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-3">
                <div class="kpi-card shadow-sm">
                    <small class="text-muted fw-bold text-uppercase">Ingresos totales</small>
                    <h3 class="fw-bold text-success mb-0">
                        ${{ number_format($totalIngresos, 2) }}
                    </h3>
                    <small class="text-muted">{{ $totalPagos }} pagos registrados</small>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="kpi-card shadow-sm">
                    <small class="text-muted fw-bold text-uppercase">Efectivo</small>
                    <h3 class="fw-bold text-success mb-0">
                        ${{ number_format($totalEfectivo, 2) }}
                    </h3>
                    <small class="text-muted">Ingresos en efectivo</small>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="kpi-card shadow-sm">
                    <small class="text-muted fw-bold text-uppercase">Tarjeta / transferencia</small>
                    <h3 class="fw-bold text-primary mb-0">
                        ${{ number_format($totalTarjeta + $totalTransferencia, 2) }}
                    </h3>
                    <small class="text-muted">
                        Tarjeta: ${{ number_format($totalTarjeta, 2) }} |
                        Transf: ${{ number_format($totalTransferencia, 2) }}
                    </small>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="kpi-card shadow-sm">
                    <small class="text-muted fw-bold text-uppercase">Asistencias</small>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalAsistencias }}</h3>
                    <small class="text-muted">
                        {{ $asistenciasPermitidas }} permitidas / {{ $asistenciasDenegadas }} denegadas
                    </small>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- Reporte de pagos --}}
            <div class="col-lg-8">
                <div class="report-card h-100">
                    <div class="report-header p-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-receipt-cutoff text-success me-2"></i> Reporte de pagos
                        </h6>
                        <span class="badge bg-success">{{ $pagos->total() }} registros</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Folio</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Concepto</th>
                                    <th>Método</th>
                                    <th>Estado</th>
                                    <th class="text-end">Monto</th>
                                    <th class="text-end pe-3">Ticket</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($pagos as $pago)
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

                                        <td class="fw-bold">
                                            {{ $pago->cliente?->nombre ?? 'Cliente eliminado' }}
                                        </td>

                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $pago->concepto }}
                                            </span>
                                            @if($pago->membresia)
                                                <br>
                                                <small class="text-muted">
                                                    Plan: {{ $pago->membresia->nombre }}
                                                </small>
                                            @endif
                                        </td>

                                        <td>
                                            @if($pago->metodo_pago === 'Efectivo')
                                                <span class="badge bg-success">Efectivo</span>
                                            @elseif($pago->metodo_pago === 'Tarjeta')
                                                <span class="badge bg-primary">Tarjeta</span>
                                            @else
                                                <span class="badge bg-info text-dark">Transferencia</span>
                                            @endif
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

                                        <td class="text-end fw-bold text-success">
                                            ${{ number_format($pago->monto, 2) }}
                                        </td>

                                        <td class="text-end pe-3">
                                            <a href="{{ route('pagos.ticket', $pago) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-printer-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">
                                            No hay pagos en el rango seleccionado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3">
                        {{ $pagos->links() }}
                    </div>
                </div>
            </div>

            {{-- Clientes vencidos --}}
            <div class="col-lg-4">
                <div class="report-card mb-4">
                    <div class="report-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Clientes vencidos
                        </h6>
                    </div>

                    <div class="p-3">
                        @forelse($clientesVencidos->take(8) as $cliente)
                            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                <div>
                                    <strong>{{ $cliente->nombre }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $cliente->nombre_membresia }}</small>
                                </div>

                                <span class="badge bg-danger">
                                    {{ $cliente->vigencia_hasta->format('d/m/Y') }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                No hay clientes vencidos.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-header p-3">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-calendar-event text-warning me-2"></i> Próximos a vencer
                        </h6>
                    </div>

                    <div class="p-3">
                        @forelse($clientesPorVencer->take(8) as $cliente)
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
                                No hay vencimientos próximos.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- Reporte de asistencias --}}
        <div class="report-card mt-4">
            <div class="report-header p-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-door-open-fill text-success me-2"></i> Reporte de asistencias
                </h6>
                <span class="badge bg-primary">{{ $asistencias->total() }} registros</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Fecha / hora</th>
                            <th>Cliente</th>
                            <th>Membresía</th>
                            <th>Resultado</th>
                            <th>Motivo</th>
                            <th>Registró</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($asistencias as $asistencia)
                            @php
                                $fechaEntrada = $asistencia->fecha_hora ?? $asistencia->created_at;
                            @endphp

                            <tr>
                                <td class="ps-3">
                                    <strong>{{ $fechaEntrada->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $fechaEntrada->format('h:i A') }}</small>
                                </td>

                                <td class="fw-bold">
                                    {{ $asistencia->cliente?->nombre ?? 'Cliente eliminado' }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $asistencia->cliente?->nombre_membresia ?? 'Sin membresía' }}
                                    </span>
                                </td>

                                <td>
                                    @if($asistencia->resultado === 'permitido')
                                        <span class="badge bg-success">Permitido</span>
                                    @else
                                        <span class="badge bg-danger">Denegado</span>
                                    @endif
                                </td>

                                <td>
                                    <small class="{{ $asistencia->resultado === 'permitido' ? 'text-success' : 'text-danger' }}">
                                        {{ $asistencia->motivo ?? 'Sin detalle' }}
                                    </small>
                                </td>

                                <td>
                                    <small class="text-muted">
                                        {{ $asistencia->user?->name ?? 'Sistema' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    No hay asistencias en el rango seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $asistencias->links() }}
            </div>
        </div>

    </div>
</x-app-layout>