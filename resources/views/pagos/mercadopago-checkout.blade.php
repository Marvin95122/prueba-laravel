<x-app-layout>
    <style>
        .mp-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,.06);
        }

        .qr-box {
            background: #f8f9fa;
            border: 2px dashed #198754;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }

        .mp-total {
            background: #eaf7ee;
            border-radius: 14px;
            padding: 18px;
            text-align: center;
        }
    </style>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 fw-bold mb-0">
                    <i class="bi bi-qr-code text-success me-2"></i>
                    Pago con Mercado Pago
                </h2>
                <small class="text-muted">
                    Escanea el QR desde tu celular o abre el enlace de pago.
                </small>
            </div>

            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
                Volver a pagos
            </a>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-5">
                <div class="mp-card p-4 h-100">
                    <h5 class="fw-bold mb-3">Detalle del cobro</h5>

                    <div class="mb-3">
                        <small class="text-muted d-block">Folio</small>
                        <strong>{{ $pago->folio ?? 'S/F' }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Cliente</small>
                        <strong>{{ $pago->cliente?->nombre ?? 'Cliente eliminado' }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Concepto</small>
                        <strong>{{ $pago->concepto }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block">Estado local</small>
                        @if($pago->estado === 'pagado')
                            <span class="badge bg-success">Pagado</span>
                        @elseif($pago->estado === 'pendiente')
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($pago->estado) }}</span>
                        @endif
                    </div>

                    @if($pago->mp_status)
                        <div class="mb-3">
                            <small class="text-muted d-block">Estado Mercado Pago</small>
                            <span class="badge bg-info text-dark">{{ $pago->mp_status }}</span>
                        </div>
                    @endif

                    <div class="mp-total mt-4">
                        <small class="text-success fw-bold text-uppercase">Total a pagar</small>
                        <h2 class="fw-bold text-success mb-0">
                            $ {{ number_format($pago->monto, 2) }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="mp-card p-4 h-100">
                    <div class="qr-box mb-4">
                        <h5 class="fw-bold mb-3">Escanear QR</h5>

                        <div class="bg-white p-3 rounded d-inline-block shadow-sm">
                            {!! QrCode::size(240)->generate($urlPago) !!}
                        </div>

                        <p class="text-muted small mt-3 mb-0">
                            Escanea este código con tu celular para abrir Mercado Pago.
                        </p>
                    </div>

                    @if($pago->estado === 'pagado')
                        <div class="alert alert-success text-center fw-bold">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Pago aprobado correctamente
                        </div>

                        <a href="{{ route('pagos.ticket', $pago) }}"
                        class="btn btn-success w-100 fw-bold py-3 mb-3">
                            <i class="bi bi-receipt-cutoff me-2"></i>
                            Ver ticket de pago
                        </a>
                    @else
                        <a href="{{ $urlPago }}" target="_blank" class="btn btn-success w-100 fw-bold py-3 mb-3">
                            <i class="bi bi-wallet2 me-2"></i>
                            Abrir Mercado Pago
                        </a>

                        <form action="{{ route('mercadopago.verificar', $pago) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-primary w-100 fw-bold" type="submit">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Verificar estado del pago
                            </button>
                        </form>
                    @endif

                    <div class="alert alert-info small mt-3 mb-0">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Esta integración usa Mercado Pago en modo sandbox para hacer las pruebas de pagar con dinero simulado
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>