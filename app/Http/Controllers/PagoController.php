<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
            'metodo_pago' => ['required', 'in:Efectivo,Tarjeta,Transferencia'],
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

        if ($requiereMembresia && $data['estado'] === 'pagado') {
            $membresia = Membresia::findOrFail($data['membresia_id']);

            $concepto = $data['tipo_pago'] === 'inscripcion'
                ? 'Inscripción - ' . $membresia->nombre
                : 'Renovación - ' . $membresia->nombre;

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
        } else {
            $concepto = match ($data['tipo_pago']) {
                'visita' => 'Visita por día',
                'producto' => $data['concepto'] ?: 'Venta de producto',
                'otro' => $data['concepto'] ?: 'Otro cobro',
                default => $data['concepto'] ?: 'Cobro general',
            };
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
            'estado' => $data['estado'],
            'fecha_pago' => now(),
            'notas' => $data['notas'] ?? null,
        ]);

        $pago->update([
            'folio' => 'PG-' . now()->format('Ymd') . '-' . str_pad($pago->id, 5, '0', STR_PAD_LEFT),
        ]);

        $mensaje = 'Pago registrado correctamente.';

        if ($requiereMembresia && $data['estado'] === 'pagado') {
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
}