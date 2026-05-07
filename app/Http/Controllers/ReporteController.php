<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::today()->startOfDay();

        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::today()->endOfDay();

        /*
         * PAGOS
         * Se usa fecha_pago cuando existe.
         * Si algún pago antiguo no tiene fecha_pago, se usa created_at.
         */
        $pagosBase = Pago::with(['cliente.membresiaPlan', 'membresia', 'user'])
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($subQuery) use ($fechaInicio, $fechaFin) {
                        $subQuery->whereNull('fecha_pago')
                            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                    });
            });

        $pagos = (clone $pagosBase)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'pagos_page')
            ->withQueryString();

        $totalIngresos = (clone $pagosBase)
            ->where('estado', 'pagado')
            ->sum('monto');

        $totalEfectivo = (clone $pagosBase)
            ->where('estado', 'pagado')
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto');

        $totalTarjeta = (clone $pagosBase)
            ->where('estado', 'pagado')
            ->where('metodo_pago', 'Tarjeta')
            ->sum('monto');

        $totalTransferencia = (clone $pagosBase)
            ->where('estado', 'pagado')
            ->where('metodo_pago', 'Transferencia')
            ->sum('monto');

        $totalPagos = (clone $pagosBase)->count();

        /*
         * ASISTENCIAS
         * Se usa fecha_hora cuando existe.
         * Si alguna asistencia antigua no tiene fecha_hora, se usa created_at.
         */
        $asistenciasBase = Asistencia::with(['cliente.membresiaPlan', 'user'])
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_hora', [$fechaInicio, $fechaFin])
                    ->orWhere(function ($subQuery) use ($fechaInicio, $fechaFin) {
                        $subQuery->whereNull('fecha_hora')
                            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);
                    });
            });

        $asistencias = (clone $asistenciasBase)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'asistencias_page')
            ->withQueryString();

        $totalAsistencias = (clone $asistenciasBase)->count();

        $asistenciasPermitidas = (clone $asistenciasBase)
            ->where('resultado', 'permitido')
            ->count();

        $asistenciasDenegadas = (clone $asistenciasBase)
            ->where('resultado', 'denegado')
            ->count();

        /*
         * CLIENTES
         */
        $clientesVencidos = Cliente::with('membresiaPlan')
            ->whereNotNull('vigencia_hasta')
            ->whereDate('vigencia_hasta', '<', today())
            ->orderBy('vigencia_hasta')
            ->get();

        $clientesPorVencer = Cliente::with('membresiaPlan')
            ->where('estado', 'activa')
            ->whereNotNull('vigencia_hasta')
            ->whereBetween('vigencia_hasta', [
                today()->toDateString(),
                today()->addDays(7)->toDateString(),
            ])
            ->orderBy('vigencia_hasta')
            ->get();

        return view('reportes.index', compact(
            'fechaInicio',
            'fechaFin',
            'pagos',
            'totalIngresos',
            'totalEfectivo',
            'totalTarjeta',
            'totalTransferencia',
            'totalPagos',
            'asistencias',
            'totalAsistencias',
            'asistenciasPermitidas',
            'asistenciasDenegadas',
            'clientesVencidos',
            'clientesPorVencer'
        ));
    }
}