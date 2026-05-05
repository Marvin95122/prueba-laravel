<x-app-layout>
    @php
        $membresiasParaJs = [];

        foreach ($membresias as $m) {
            $membresiasParaJs[$m->id] = [
                'nombre' => $m->nombre,
                'precio' => (float) $m->precio,
                'duracion' => $m->duracion_dias,
            ];
        }
    @endphp

    <style>
        .card-custom {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 12px;
        }

        .bg-header-custom {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .caja-total {
            background-color: #eaf7ee;
            border: 2px dashed #1f7c34;
            border-radius: 12px;
        }

        .mini-kpi {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
        }

        .ticket-box {
            background: #f8f9fa;
            border: 1px dashed #adb5bd;
            border-radius: 10px;
        }

        .input-money {
            font-size: 1.2rem;
            font-weight: 700;
            color: #198754;
        }

        .table td, .table th {
            vertical-align: middle;
        }
    </style>

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark fw-bold">
                    <i class="bi bi-cash-coin text-success me-2"></i> Control de Pagos
                </h2>
                <small class="text-muted">
                    Cobros, renovaciones, cambio en efectivo y control de caja.
                </small>
            </div>

            <div class="caja-total px-4 py-2 text-center shadow-sm">
                <span class="d-block text-success fw-bold small text-uppercase">Total Caja Hoy</span>
                <span class="fs-4 fw-bold text-dark">$ {{ number_format($totalCaja, 2) }}</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
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
                    <span class="text-muted small fw-bold text-uppercase">Efectivo</span>
                    <h4 class="fw-bold mb-0 text-success">$ {{ number_format($totalEfectivo, 2) }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Tarjeta</span>
                    <h4 class="fw-bold mb-0 text-primary">$ {{ number_format($totalTarjeta, 2) }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Transferencia</span>
                    <h4 class="fw-bold mb-0 text-info">$ {{ number_format($totalTransferencia, 2) }}</h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mini-kpi p-3 shadow-sm">
                    <span class="text-muted small fw-bold text-uppercase">Tickets / Renovaciones</span>
                    <h4 class="fw-bold mb-0 text-dark">{{ $totalTickets }} / {{ $totalRenovaciones }}</h4>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- Formulario de pago --}}
            <div class="col-lg-4">
                <div class="card card-custom shadow-sm border-top border-success border-4 h-100">
                    <div class="card-header bg-header-custom py-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-receipt-cutoff text-success me-2"></i> Registrar Cobro
                        </h6>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('pagos.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cliente</label>
                                <select name="cliente_id" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona un cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" @selected(old('cliente_id') == $cliente->id)>
                                            {{ $cliente->nombre }} - {{ $cliente->nombre_membresia }}
                                            @if($cliente->vigencia_hasta)
                                                (vence {{ $cliente->vigencia_hasta->format('d/m/Y') }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tipo de cobro</label>
                                <select name="tipo_pago" id="tipo_pago" class="form-select" required>
                                    <option value="renovacion" @selected(old('tipo_pago') === 'renovacion')>Renovación de membresía</option>
                                    <option value="inscripcion" @selected(old('tipo_pago') === 'inscripcion')>Inscripción</option>
                                    <option value="visita" @selected(old('tipo_pago') === 'visita')>Visita por día</option>
                                    <option value="producto" @selected(old('tipo_pago') === 'producto')>Producto / Suplemento</option>
                                    <option value="otro" @selected(old('tipo_pago') === 'otro')>Otro cobro</option>
                                </select>
                            </div>

                            <div class="mb-3" id="grupo_membresia">
                                <label class="form-label fw-semibold">Membresía</label>
                                <select name="membresia_id" id="membresia_id" class="form-select">
                                    <option value="">-- Selecciona una membresía --</option>
                                    @foreach($membresias as $membresia)
                                        <option value="{{ $membresia->id }}" @selected(old('membresia_id') == $membresia->id)>
                                            {{ $membresia->nombre }} - ${{ number_format($membresia->precio, 2) }} / {{ $membresia->duracion_dias }} días
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Concepto</label>
                                <input type="text" name="concepto" id="concepto" class="form-control"
                                       value="{{ old('concepto') }}"
                                       placeholder="Ej. Renovación, visita, producto...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Monto a pagar</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="1" name="monto" id="monto"
                                           class="form-control input-money"
                                           value="{{ old('monto') }}"
                                           placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Método de pago</label>
                                <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                                    <option value="Efectivo" @selected(old('metodo_pago') === 'Efectivo')>Efectivo</option>
                                    <option value="Tarjeta" @selected(old('metodo_pago') === 'Tarjeta')>Tarjeta</option>
                                    <option value="Transferencia" @selected(old('metodo_pago') === 'Transferencia')>Transferencia</option>
                                </select>
                            </div>

                            <div class="mb-3" id="grupo_recibido">
                                <label class="form-label fw-semibold">Cantidad recibida</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" min="0" name="monto_recibido" id="monto_recibido"
                                           class="form-control input-money"
                                           value="{{ old('monto_recibido') }}"
                                           placeholder="0.00">
                                </div>
                                <small class="text-muted">Solo aplica para pagos en efectivo.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cambio</label>
                                <div class="ticket-box p-3">
                                    <h3 class="mb-0 fw-bold text-success" id="cambio_visible">$ 0.00</h3>
                                    <small class="text-muted">Cambio que se entrega al cliente.</small>
                                </div>
                            </div>

                            <div class="mb-3" id="grupo_referencia">
                                <label class="form-label fw-semibold">Referencia</label>
                                <input type="text" name="referencia" class="form-control"
                                       value="{{ old('referencia') }}"
                                       placeholder="Ej. TRANSF-4589 / TARJ-1020">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Estado del pago</label>
                                <select name="estado" class="form-select" required>
                                    <option value="pagado" @selected(old('estado', 'pagado') === 'pagado')>Pagado</option>
                                    <option value="pendiente" @selected(old('estado') === 'pendiente')>Pendiente</option>
                                    <option value="cancelado" @selected(old('estado') === 'cancelado')>Cancelado</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Notas</label>
                                <textarea name="notas" rows="2" class="form-control"
                                          placeholder="Observaciones opcionales">{{ old('notas') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-cart-check-fill me-2"></i> Registrar pago
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Historial --}}
            <div class="col-lg-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-header-custom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="bi bi-clock-history text-success me-2"></i> Historial de pagos
                        </h6>
                        <span class="badge bg-success">{{ $pagos->total() }} registros</span>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Folio</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Concepto</th>
                                        <th>Método</th>
                                        <th>Recibido</th>
                                        <th>Cambio</th>
                                        <th>Estado</th>
                                        <th class="text-end pe-4">Monto</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($pagos as $pago)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-dark">
                                                    {{ $pago->folio ?? 'S/F' }}
                                                </span>
                                            </td>

                                            <td>
                                                <small class="text-muted">
                                                    {{ ($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y h:i A') }}
                                                </small>
                                            </td>

                                            <td class="fw-bold text-dark">
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
                                                    <span class="badge bg-success-subtle text-success border border-success">
                                                        <i class="bi bi-cash"></i> Efectivo
                                                    </span>
                                                @elseif($pago->metodo_pago === 'Tarjeta')
                                                    <span class="badge bg-primary-subtle text-primary border border-primary">
                                                        <i class="bi bi-credit-card"></i> Tarjeta
                                                    </span>
                                                @else
                                                    <span class="badge bg-info-subtle text-info border border-info">
                                                        <i class="bi bi-bank"></i> Transferencia
                                                    </span>
                                                @endif

                                                @if($pago->referencia)
                                                    <br>
                                                    <small class="text-muted">Ref: {{ $pago->referencia }}</small>
                                                @endif
                                            </td>

                                            <td>$ {{ number_format($pago->monto_recibido ?? 0, 2) }}</td>
                                            <td class="fw-bold text-success">$ {{ number_format($pago->cambio ?? 0, 2) }}</td>

                                            <td>
                                                @if($pago->estado === 'pagado')
                                                    <span class="badge bg-success">Pagado</span>
                                                @elseif($pago->estado === 'pendiente')
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                @else
                                                    <span class="badge bg-danger">Cancelado</span>
                                                @endif
                                            </td>

                                            <td class="text-end pe-4 fw-bold text-success">
                                                $ {{ number_format($pago->monto, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5 text-muted">
                                                Aún no hay pagos registrados.
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
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const membresias = @json($membresiasParaJs);

            const tipoPago = document.getElementById('tipo_pago');
            const membresiaSelect = document.getElementById('membresia_id');
            const montoInput = document.getElementById('monto');
            const conceptoInput = document.getElementById('concepto');
            const metodoPago = document.getElementById('metodo_pago');
            const recibidoInput = document.getElementById('monto_recibido');
            const cambioVisible = document.getElementById('cambio_visible');

            const grupoMembresia = document.getElementById('grupo_membresia');
            const grupoRecibido = document.getElementById('grupo_recibido');
            const grupoReferencia = document.getElementById('grupo_referencia');

            function money(value) {
                return '$ ' + Number(value || 0).toFixed(2);
            }

            function actualizarFormulario() {
                const tipo = tipoPago.value;
                const requiereMembresia = tipo === 'renovacion' || tipo === 'inscripcion';

                grupoMembresia.style.display = requiereMembresia ? 'block' : 'none';

                if (requiereMembresia && membresiaSelect.value && membresias[membresiaSelect.value]) {
                    const plan = membresias[membresiaSelect.value];
                    montoInput.value = plan.precio;
                    conceptoInput.value = tipo === 'inscripcion'
                        ? 'Inscripción - ' + plan.nombre
                        : 'Renovación - ' + plan.nombre;
                }

                if (tipo === 'visita') {
                    montoInput.value = 60;
                    conceptoInput.value = 'Visita por día';
                }

                if (tipo === 'producto') {
                    conceptoInput.placeholder = 'Ej. Agua, proteína, guantes...';
                    if (!conceptoInput.value.includes('Venta')) {
                        conceptoInput.value = '';
                    }
                    if (!montoInput.value || montoInput.value == 60) {
                        montoInput.value = '';
                    }
                }

                if (tipo === 'otro') {
                    conceptoInput.placeholder = 'Describe el cobro';
                    if (!montoInput.value || montoInput.value == 60) {
                        montoInput.value = '';
                    }
                }

                actualizarMetodoPago();
                calcularCambio();
            }

            function actualizarMetodoPago() {
                const metodo = metodoPago.value;

                if (metodo === 'Efectivo') {
                    grupoRecibido.style.display = 'block';
                    grupoReferencia.style.display = 'none';
                } else {
                    grupoRecibido.style.display = 'none';
                    grupoReferencia.style.display = 'block';
                    recibidoInput.value = montoInput.value || 0;
                }

                calcularCambio();
            }

            function calcularCambio() {
                const metodo = metodoPago.value;
                const monto = parseFloat(montoInput.value) || 0;
                const recibido = parseFloat(recibidoInput.value) || 0;

                if (metodo === 'Efectivo') {
                    const cambio = recibido - monto;
                    cambioVisible.textContent = money(cambio > 0 ? cambio : 0);
                } else {
                    cambioVisible.textContent = money(0);
                }
            }

            tipoPago.addEventListener('change', actualizarFormulario);
            membresiaSelect.addEventListener('change', actualizarFormulario);
            metodoPago.addEventListener('change', actualizarMetodoPago);
            recibidoInput.addEventListener('input', calcularCambio);
            montoInput.addEventListener('input', calcularCambio);

            actualizarFormulario();
        });
    </script>
</x-app-layout>