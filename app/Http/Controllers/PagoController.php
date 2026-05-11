<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::with('membresiaPlan')
            ->orderBy('nombre')
            ->get();

        $membresias = Membresia::where('estado', 'activa')
            ->orderBy('precio')
            ->get();

        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::today()->startOfDay();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::today()->endOfDay();

        $pagosConsulta = Pago::with(['cliente.membresiaPlan', 'membresia', 'user'])
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                        $q->whereNull('fecha_pago')
                            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                    });
            })
            ->when($request->cliente_id, function ($query, $clienteId) {
                $query->where('cliente_id', $clienteId);
            })
            ->when($request->metodo_pago, function ($query, $metodo) {
                $query->where('metodo_pago', $metodo);
            })
            ->when($request->tipo_pago, function ($query, $tipo) {
                $query->where('tipo_pago', $tipo);
            })
            ->when($request->estado, function ($query, $estado) {
                $query->where('estado', $estado);
            });

        $pagosHoy = (clone $pagosConsulta)
            ->orderBy('created_at', 'desc')
            ->get();

        $pagos = (clone $pagosConsulta)
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $totalCaja = $pagosHoy->where('estado', 'pagado')->sum('monto');
        $totalEfectivo = $pagosHoy->where('estado', 'pagado')->where('metodo_pago', 'Efectivo')->sum('monto');
        $totalTarjeta = $pagosHoy->where('estado', 'pagado')->where('metodo_pago', 'Tarjeta')->sum('monto');
        $totalTransferencia = $pagosHoy->where('estado', 'pagado')->where('metodo_pago', 'Transferencia')->sum('monto');

        $totalTickets = $pagosHoy->count();
        $totalRenovaciones = $pagosHoy->whereIn('tipo_pago', ['renovacion', 'inscripcion'])->count();

        return view('pagos.index', compact(
            'clientes',
            'membresias',
            'pagosHoy',
            'pagos',
            'totalCaja',
            'totalEfectivo',
            'totalTarjeta',
            'totalTransferencia',
            'totalTickets',
            'totalRenovaciones',
            'fechaInicio',
            'fechaFin'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'tipo_pago' => ['required', 'in:renovacion,inscripcion,visita,producto,otro'],
            'membresia_id' => ['nullable', 'exists:membresias,id'],
            'concepto' => ['nullable', 'string', 'max:120'],
            'monto' => ['required', 'numeric', 'min:1'],
            'monto_recibido' => ['nullable', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'in:Efectivo,Tarjeta,Transferencia,Mercado Pago'],
            'referencia' => ['nullable', 'string', 'max:120'],
            'estado' => ['required', 'in:pagado,pendiente,cancelado'],
            'notas' => ['nullable', 'string', 'max:500'],
        ]);

        $cliente = Cliente::findOrFail($data['cliente_id']);

        $requiereMembresia = in_array($data['tipo_pago'], ['renovacion', 'inscripcion'], true);

        if ($requiereMembresia && empty($data['membresia_id'])) {
            return back()
                ->withErrors(['membresia_id' => 'Debes seleccionar una membresía para inscripción o renovación.'])
                ->withInput();
        }

        $membresia = null;
        $nuevaVigencia = null;

        $monto = (float) $data['monto'];
        $montoRecibido = $data['monto_recibido'] !== null ? (float) $data['monto_recibido'] : 0;
        $cambio = 0;

        if ($data['metodo_pago'] === 'Efectivo') {
            if ($montoRecibido < $monto) {
                return back()
                    ->withErrors(['monto_recibido' => 'La cantidad recibida no puede ser menor al monto a pagar.'])
                    ->withInput();
            }

            $cambio = $montoRecibido - $monto;
        } else {
            $montoRecibido = $monto;
            $cambio = 0;
        }

        if ($requiereMembresia) {
            $membresia = Membresia::findOrFail($data['membresia_id']);

            $concepto = $data['tipo_pago'] === 'inscripcion'
                ? 'Inscripción - ' . $membresia->nombre
                : 'Renovación - ' . $membresia->nombre;
        } else {
            $concepto = match ($data['tipo_pago']) {
                'visita' => 'Visita por día',
                'producto' => $data['concepto'] ?: 'Venta de producto',
                'otro' => $data['concepto'] ?: 'Otro cobro',
                default => $data['concepto'] ?: 'Cobro general',
            };
        }

        /*
         * Si el método es Mercado Pago, el pago se guarda como pendiente.
         * La membresía se renovará hasta que Mercado Pago confirme el pago.
         */
        $estadoInicial = $data['metodo_pago'] === 'Mercado Pago'
            ? 'pendiente'
            : $data['estado'];

        if ($requiereMembresia && $estadoInicial === 'pagado') {
            $fechaBase = $cliente->vigencia_hasta && $cliente->vigencia_hasta->gte(today())
                ? $cliente->vigencia_hasta->copy()
                : today();

            $nuevaVigencia = $fechaBase->copy()->addDays($membresia->duracion_dias);

            $cliente->update([
                'membresia_id' => $membresia->id,
                'membresia' => strtolower($membresia->nombre),
                'vigencia_hasta' => $nuevaVigencia->toDateString(),
                'estado' => 'activa',
            ]);
        }

        if ($data['metodo_pago'] === 'Mercado Pago') {
            $pagoPendiente = Pago::where('cliente_id', $cliente->id)
                ->where('metodo_pago', 'Mercado Pago')
                ->where('estado', 'pendiente')
                ->where('tipo_pago', $data['tipo_pago'])
                ->where('monto', $monto)
                ->where('created_at', '>=', now()->subMinutes(15))
                ->latest()
                ->first();

            if ($pagoPendiente) {
                if ($pagoPendiente->mp_preference_id) {
                    return redirect()
                        ->route('mercadopago.checkout', $pagoPendiente)
                        ->with('success', 'Ya existía un cobro pendiente reciente. Se abrió el mismo QR.');
                }

                return redirect()
                    ->route('mercadopago.generar', $pagoPendiente)
                    ->with('success', 'Ya existía un cobro pendiente reciente. Se intentará generar nuevamente Mercado Pago.');
            }
        }
        $pago = Pago::create([
            'cliente_id' => $cliente->id,
            'membresia_id' => $membresia?->id,
            'user_id' => auth()->id(),
            'monto' => $monto,
            'monto_recibido' => $montoRecibido,
            'cambio' => $cambio,
            'concepto' => $concepto,
            'tipo_pago' => $data['tipo_pago'],
            'metodo_pago' => $data['metodo_pago'],
            'referencia' => $data['referencia'] ?? null,
            'estado' => $estadoInicial,
            'fecha_pago' => $estadoInicial === 'pagado' ? now() : null,
            'notas' => $data['notas'] ?? null,
        ]);

        $pago->update([
            'folio' => 'PG-' . now()->format('Ymd') . '-' . str_pad($pago->id, 5, '0', STR_PAD_LEFT),
        ]);

        if ($data['metodo_pago'] === 'Mercado Pago') {
            return redirect()->route('mercadopago.generar', $pago);
        }

        $mensaje = 'Pago registrado correctamente.';

        if ($requiereMembresia && $estadoInicial === 'pagado') {
            $mensaje = 'Pago registrado y membresía actualizada. Nueva vigencia: ' . $nuevaVigencia->format('d/m/Y');
        }

        return redirect()
            ->route('pagos.index')
            ->with('success', $mensaje);
    }

    public function ticket(Pago $pago)
    {
        $pago->load(['cliente.membresiaPlan', 'membresia', 'user']);

        return view('pagos.ticket', compact('pago'));
    }

    private function aplicarRenovacionSiCorresponde(Pago $pago): void
    {
        if (!in_array($pago->tipo_pago, ['renovacion', 'inscripcion'], true)) {
            return;
        }

        if (!$pago->cliente || !$pago->membresia) {
            return;
        }

        $cliente = $pago->cliente;
        $membresia = $pago->membresia;

        $fechaBase = $cliente->vigencia_hasta && $cliente->vigencia_hasta->gte(today())
            ? $cliente->vigencia_hasta->copy()
            : today();

        $nuevaVigencia = $fechaBase->copy()->addDays($membresia->duracion_dias);

        $cliente->update([
            'membresia_id' => $membresia->id,
            'membresia' => strtolower($membresia->nombre),
            'vigencia_hasta' => $nuevaVigencia->toDateString(),
            'estado' => 'activa',
        ]);
    }

    public function generarMercadoPago(Pago $pago)
    {
        $pago->load(['cliente.membresiaPlan', 'membresia', 'user']);

        if ($pago->estado === 'pagado') {
            return redirect()
                ->route('pagos.ticket', $pago)
                ->with('success', 'Este pago ya se encuentra pagado.');
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        $externalReference = $pago->mp_external_reference ?: 'GYM-PAGO-' . $pago->id . '-' . now()->timestamp;

        $client = new PreferenceClient();

        $preferenceData = [
            'items' => [
                [
                    'title' => $pago->concepto ?: 'Pago GymControl',
                    'quantity' => 1,
                    'unit_price' => (float) $pago->monto,
                    'currency_id' => 'MXN',
                ],
            ],
            'external_reference' => $externalReference,
            'payer' => [
                'name' => $pago->cliente?->nombre ?? 'Cliente GymControl',
            ],
            'statement_descriptor' => 'GYMCONTROL',
        ];

        /*
        * En local no mandamos back_urls ni notification_url porque 127.0.0.1/http
        * puede ser rechazado por Mercado Pago.
        * Cuando usemos ngrok o servidor HTTPS, las agregamos de nuevo.
        */
        $baseUrl = rtrim(config('app.url'), '/');

        /*
        * Si por error APP_URL quedó con http en ngrok,
        * lo forzamos a https para que Mercado Pago acepte las back_urls.
        */
        if (str_contains($baseUrl, 'ngrok') && str_starts_with($baseUrl, 'http://')) {
            $baseUrl = str_replace('http://', 'https://', $baseUrl);
        }

        $esLocal = str_contains($baseUrl, '127.0.0.1')
            || str_contains($baseUrl, 'localhost')
            || !str_starts_with($baseUrl, 'https://');

        if (!$esLocal) {
            $preferenceData['back_urls'] = [
                'success' => $baseUrl . '/pagos/' . $pago->id . '/mercadopago/success',
                'failure' => $baseUrl . '/pagos/' . $pago->id . '/mercadopago/failure',
                'pending' => $baseUrl . '/pagos/' . $pago->id . '/mercadopago/pending',
            ];

            $preferenceData['auto_return'] = 'approved';
            $preferenceData['notification_url'] = $baseUrl . '/mercadopago/webhook';
        }

        try {
            $preference = $client->create($preferenceData);
        } catch (MPApiException $e) {
            $apiResponse = $e->getApiResponse();

            $statusCode = $apiResponse?->getStatusCode();
            $content = $apiResponse?->getContent();

            return redirect()
                ->route('pagos.index')
                ->with('error', 'Error Mercado Pago ' . $statusCode . ': ' . json_encode($content));
        } catch (\Exception $e) {
            return redirect()
                ->route('pagos.index')
                ->with('error', 'Error general al generar Mercado Pago: ' . $e->getMessage());
        }

        $pago->update([
            'mp_preference_id' => $preference->id ?? null,
            'mp_external_reference' => $externalReference,
            'mp_init_point' => $preference->init_point ?? null,
            'mp_sandbox_init_point' => $preference->sandbox_init_point ?? null,
            'mp_status' => 'created',
            'estado' => 'pendiente',
        ]);

        return redirect()->route('mercadopago.checkout', $pago);
    }

    public function checkoutMercadoPago(Pago $pago)
    {
        $pago->load(['cliente.membresiaPlan', 'membresia', 'user']);

        $urlPago = config('services.mercadopago.sandbox')
            ? ($pago->mp_sandbox_init_point ?: $pago->mp_init_point)
            : ($pago->mp_init_point ?: $pago->mp_sandbox_init_point);

        if (!$urlPago) {
            return redirect()
                ->route('pagos.index')
                ->with('error', 'Este pago no tiene enlace de Mercado Pago. Genera nuevamente la preferencia.');
        }

        return view('pagos.mercadopago-checkout', compact('pago', 'urlPago'));
    }

    public function successMercadoPago(Pago $pago)
    {
        $pago->load(['cliente', 'membresia']);

        $paymentId = request('payment_id');

        if ($paymentId) {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            $paymentClient = new PaymentClient();
            $payment = $paymentClient->get($paymentId);

            $status = $payment->status ?? request('status');

            $pago->update([
                'mp_payment_id' => $paymentId,
                'mp_status' => $status,
            ]);

            if ($status === 'approved') {
                $pago->update([
                    'estado' => 'pagado',
                    'fecha_pago' => now(),
                    'monto_recibido' => $pago->monto,
                    'cambio' => 0,
                ]);

                $this->aplicarRenovacionSiCorresponde($pago);

                return redirect()
                    ->route('pagos.ticket', $pago)
                    ->with('success', 'Pago aprobado por Mercado Pago.');
            }
        }

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Mercado Pago regresó al sistema. Revisa el estado del pago.');
    }

    public function pendingMercadoPago(Pago $pago)
    {
        $pago->update([
            'mp_status' => request('status', 'pending'),
            'estado' => 'pendiente',
        ]);

        return redirect()
            ->route('pagos.index')
            ->with('success', 'El pago quedó pendiente en Mercado Pago.');
    }

    public function failureMercadoPago(Pago $pago)
    {
        $pago->update([
            'mp_status' => request('status', 'failure'),
        ]);

        return redirect()
            ->route('pagos.index')
            ->with('error', 'El pago no fue aprobado o fue cancelado.');
    }

    public function verificarMercadoPago(Pago $pago)
    {
        try {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            $paymentData = null;

            if ($pago->mp_payment_id) {
                $paymentClient = new PaymentClient();
                $payment = $paymentClient->get($pago->mp_payment_id);

                $paymentData = json_decode(json_encode($payment), true);
            } else {
                $paymentData = $this->buscarPagoMercadoPagoPorReferencia($pago->mp_external_reference);
            }

            if (!$paymentData) {
                return back()->with(
                    'error',
                    'No se encontró un pago aprobado todavía en Mercado Pago. Espera unos segundos y vuelve a verificar.'
                );
            }

            $this->actualizarPagoDesdeMercadoPago($pago, $paymentData);

            $pago->refresh();

            if ($pago->estado === 'pagado') {
                return redirect()
                    ->route('pagos.ticket', $pago)
                    ->with('success', 'Pago aprobado y actualizado desde Mercado Pago.');
            }

            return back()->with(
                'success',
                'Estado consultado en Mercado Pago: ' . ($pago->mp_status ?? 'sin estado')
            );
        } catch (\Exception $e) {
            return back()->with(
                'error',
                'No se pudo verificar el pago en Mercado Pago: ' . $e->getMessage()
            );
        }
    }

    private function buscarPagoMercadoPagoPorReferencia(?string $externalReference): ?array
    {
        if (!$externalReference) {
            return null;
        }

        $response = Http::withToken(config('services.mercadopago.access_token'))
            ->acceptJson()
            ->timeout(20)
            ->get('https://api.mercadopago.com/v1/payments/search', [
                'external_reference' => $externalReference,
                'sort' => 'date_created',
                'criteria' => 'desc',
            ]);

        if (!$response->successful()) {
            throw new \Exception(
                'Error consultando payments/search. Código: ' . $response->status() . ' - ' . $response->body()
            );
        }

        $resultados = $response->json('results') ?? [];

        if (empty($resultados)) {
            return null;
        }

        return collect($resultados)->firstWhere('status', 'approved') ?? $resultados[0];
    }

    private function actualizarPagoDesdeMercadoPago(Pago $pago, array $paymentData): void
    {
        $status = $paymentData['status'] ?? null;
        $paymentId = $paymentData['id'] ?? null;

        $pago->update([
            'mp_payment_id' => $paymentId ?: $pago->mp_payment_id,
            'mp_status' => $status ?: $pago->mp_status,
        ]);

        if ($status === 'approved' && $pago->estado !== 'pagado') {
            $pago->update([
                'estado' => 'pagado',
                'fecha_pago' => now(),
                'monto_recibido' => $pago->monto,
                'cambio' => 0,
                'referencia' => $pago->referencia ?: 'Mercado Pago ' . $paymentId,
            ]);

            $pago->load(['cliente', 'membresia']);

            $this->aplicarRenovacionSiCorresponde($pago);
        }
    }

    public function webhookMercadoPago()
    {
        $type = request('type') ?? request('topic');
        $paymentId = request('data.id') ?? request('id');

        if ($type === 'payment' && $paymentId) {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

            $paymentClient = new PaymentClient();
            $payment = $paymentClient->get($paymentId);

            $externalReference = $payment->external_reference ?? null;

            if ($externalReference) {
                $pago = Pago::where('mp_external_reference', $externalReference)->first();

                if ($pago) {
                    $pago->update([
                        'mp_payment_id' => $paymentId,
                        'mp_status' => $payment->status ?? null,
                    ]);

                    if (($payment->status ?? null) === 'approved' && $pago->estado !== 'pagado') {
                        $pago->update([
                            'estado' => 'pagado',
                            'fecha_pago' => now(),
                            'monto_recibido' => $pago->monto,
                            'cambio' => 0,
                        ]);

                        $pago->load(['cliente', 'membresia']);
                        $this->aplicarRenovacionSiCorresponde($pago);
                    }
                }
            }
        }

        return response()->json(['ok' => true], 200);
    }
}