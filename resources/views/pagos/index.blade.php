<x-app-layout>
    <style>
        .card-custom { background-color: white; border: 1px solid #dee2e6; border-radius: 10px; }
        .bg-header-custom { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; border-top-left-radius: 10px; border-top-right-radius: 10px; }
        .caja-total { background-color: #eaf7ee; border: 2px dashed #1f7c34; border-radius: 10px; }
    </style>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-0 text-dark fw-bold"><i class="bi bi-cash-coin text-success me-2"></i> Control de Pagos</h2>
                <small class="text-muted">Punto de venta y registro de ingresos diarios.</small>
            </div>
            
            <div class="caja-total px-4 py-2 text-center shadow-sm">
                <span class="d-block text-success fw-bold small text-uppercase">Total en Caja (Hoy)</span>
                <span class="fs-4 fw-bold text-dark">$ {{ number_format($totalCaja, 2) }}</span>
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
                <div class="card card-custom shadow-sm border-top border-success border-4 h-100">
                    <div class="card-header bg-header-custom py-3">
                        <h6 class="fw-bold mb-0 text-dark">Registrar Nuevo Cobro</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pagos.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cliente</label>
                                <select name="cliente_id" class="form-select" required>
                                    <option value="" disabled selected>-- Buscar cliente --</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Concepto</label>
                                <select name="concepto" id="concepto" class="form-select" required>
                                    <option value="" disabled selected>-- Selecciona un concepto --</option>
                                    <option value="Mensualidad Básica">Mensualidad Básica</option>
                                    <option value="Mensualidad Plus">Mensualidad Plus</option>
                                    <option value="Mensualidad Premium VIP">Mensualidad Premium VIP</option>
                                    <option value="Inscripción">Inscripción</option>
                                    <option value="Visita por día">Visita por día</option>
                                    <option value="Agua / Suplemento">Agua / Suplemento</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Monto ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">$</span>
                                    <input type="number" step="0.01" name="monto" id="monto" class="form-control fw-bold text-success" required placeholder="0.00">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Método de Pago</label>
                                <select name="metodo_pago" class="form-select" required>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                                    <option value="Transferencia">Transferencia</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-cart-check-fill me-2"></i> Cobrar y Guardar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-custom shadow-sm h-100">
                    <div class="card-header bg-header-custom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">Historial de Ingresos del Día</h6>
                        <span class="badge bg-success">{{ $pagosHoy->count() }} tickets</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Hora</th>
                                        <th>Cliente</th>
                                        <th>Concepto</th>
                                        <th>Método</th>
                                        <th class="text-end pe-4">Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pagosHoy as $pago)
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">{{ $pago->created_at->format('h:i A') }}</td>
                                            <td class="fw-bold text-dark">{{ $pago->cliente->nombre }}</td>
                                            <td><span class="badge bg-secondary">{{ $pago->concepto }}</span></td>
                                            <td><i class="bi {{ $pago->metodo_pago == 'Efectivo' ? 'bi-cash' : 'bi-credit-card' }} me-1 text-muted"></i> {{ $pago->metodo_pago }}</td>
                                            <td class="text-end pe-4 fw-bold text-success">$ {{ number_format($pago->monto, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">Aún no hay cobros registrados hoy.</td>
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
        document.addEventListener('DOMContentLoaded', function() {
            const precios = {
                'Mensualidad Básica': 399,
                'Mensualidad Plus': 599,
                'Mensualidad Premium VIP': 899,
                'Inscripción': 200,
                'Visita por día': 60,
                'Agua / Suplemento': 15
            };

            const selectConcepto = document.getElementById('concepto');
            const inputMonto = document.getElementById('monto');

            selectConcepto.addEventListener('change', function() {
                if (precios[this.value]) {
                    inputMonto.value = precios[this.value];
                } else {
                    inputMonto.value = '';
                }
            });
        });
    </script>
</x-app-layout>