<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Pago - {{ $pago->folio ?? 'Sin folio' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #f1f3f5;
            font-family: Arial, sans-serif;
        }

        .ticket-wrapper {
            max-width: 420px;
            margin: 30px auto;
        }

        .ticket {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,.08);
        }

        .ticket-header {
            text-align: center;
            border-bottom: 1px dashed #adb5bd;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .ticket-logo {
            font-size: 28px;
            font-weight: 800;
            color: #198754;
        }

        .ticket-subtitle {
            color: #6c757d;
            font-size: 13px;
        }

        .ticket-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 14px;
            padding: 6px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .ticket-row span:first-child {
            color: #6c757d;
        }

        .ticket-row span:last-child {
            font-weight: 700;
            text-align: right;
        }

        .ticket-total {
            background: #eaf7ee;
            border: 1px dashed #198754;
            border-radius: 10px;
            padding: 14px;
            margin-top: 18px;
            text-align: center;
        }

        .ticket-total small {
            display: block;
            color: #198754;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .ticket-total h2 {
            margin: 0;
            color: #198754;
            font-weight: 800;
        }

        .ticket-footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 18px;
            border-top: 1px dashed #adb5bd;
            padding-top: 15px;
        }

        .screen-actions {
            max-width: 420px;
            margin: 20px auto;
            display: flex;
            gap: 10px;
        }

        @media print {
            body {
                background: white;
            }

            .screen-actions {
                display: none !important;
            }

            .ticket-wrapper {
                margin: 0 auto;
            }

            .ticket {
                box-shadow: none;
                border: none;
                border-radius: 0;
            }

            @page {
                size: 80mm auto;
                margin: 5mm;
            }
        }
    </style>
</head>
<body>

    <div class="ticket-wrapper">
        <div class="ticket">

            <div class="ticket-header">
                <div class="ticket-logo">
                    <i class="bi bi-heart-pulse-fill"></i> GymControl
                </div>
                <div class="ticket-subtitle">
                    Comprobante de pago de membresía
                </div>
                <div class="ticket-subtitle">
                    {{ now()->format('d/m/Y h:i A') }}
                </div>
            </div>

            <div class="ticket-row">
                <span>Folio</span>
                <span>{{ $pago->folio ?? 'S/F' }}</span>
            </div>

            <div class="ticket-row">
                <span>Fecha de pago</span>
                <span>{{ ($pago->fecha_pago ?? $pago->created_at)->format('d/m/Y h:i A') }}</span>
            </div>

            <div class="ticket-row">
                <span>Cliente</span>
                <span>{{ $pago->cliente?->nombre ?? 'Cliente eliminado' }}</span>
            </div>

            <div class="ticket-row">
                <span>Membresía</span>
                <span>
                    {{ $pago->membresia?->nombre ?? $pago->cliente?->nombre_membresia ?? 'N/A' }}
                </span>
            </div>

            @if($pago->cliente?->vigencia_hasta)
                <div class="ticket-row">
                    <span>Vigencia actual</span>
                    <span>{{ $pago->cliente->vigencia_hasta->format('d/m/Y') }}</span>
                </div>
            @endif

            <div class="ticket-row">
                <span>Concepto</span>
                <span>{{ $pago->concepto }}</span>
            </div>

            <div class="ticket-row">
                <span>Tipo de cobro</span>
                <span>{{ ucfirst($pago->tipo_pago ?? 'Pago') }}</span>
            </div>

            <div class="ticket-row">
                <span>Método</span>
                <span>{{ $pago->metodo_pago }}</span>
            </div>

            @if($pago->referencia)
                <div class="ticket-row">
                    <span>Referencia</span>
                    <span>{{ $pago->referencia }}</span>
                </div>
            @endif

            <div class="ticket-row">
                <span>Estado</span>
                <span>{{ ucfirst($pago->estado ?? 'pagado') }}</span>
            </div>

            <div class="ticket-row">
                <span>Cobró</span>
                <span>{{ $pago->user?->name ?? 'Sistema' }}</span>
            </div>

            <div class="ticket-row">
                <span>Monto recibido</span>
                <span>$ {{ number_format($pago->monto_recibido ?? $pago->monto, 2) }}</span>
            </div>

            <div class="ticket-row">
                <span>Cambio</span>
                <span>$ {{ number_format($pago->cambio ?? 0, 2) }}</span>
            </div>

            <div class="ticket-total">
                <small>Total pagado</small>
                <h2>$ {{ number_format($pago->monto, 2) }}</h2>
            </div>

            @if($pago->notas)
                <div class="mt-3">
                    <small class="text-muted fw-bold">Notas:</small>
                    <p class="small mb-0">{{ $pago->notas }}</p>
                </div>
            @endif

            <div class="ticket-footer">
                <p class="mb-1">Gracias por tu pago.</p>
                <p class="mb-1">Conserva este comprobante para cualquier aclaración.</p>
                <p class="mb-0">&copy; 2026 GymControl</p>
            </div>

        </div>
    </div>

    <div class="screen-actions">
        <button onclick="window.print()" class="btn btn-success w-100 fw-bold">
            <i class="bi bi-printer-fill"></i> Imprimir
        </button>

        <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary w-100">
            Volver
        </a>
    </div>

</body>
</html>