<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\Membresia;
use App\Models\Pago;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $finSemana = Carbon::today()->addDays(7);

        // Clientes
        $totalClientes = Cliente::count();

        $clientesActivos = Cliente::where('estado', 'activa')->count();

        $clientesInactivos = Cliente::where('estado', '!=', 'activa')->count();

        $clientesVencidos = Cliente::whereNotNull('vigencia_hasta')
            ->whereDate('vigencia_hasta', '<', $hoy)
            ->count();

        $clientesPorVencer = Cliente::with('membresiaPlan')
            ->where('estado', 'activa')
            ->whereNotNull('vigencia_hasta')
            ->whereBetween('vigencia_hasta', [$hoy->toDateString(), $finSemana->toDateString()])
            ->orderBy('vigencia_hasta')
            ->limit(6)
            ->get();

        // Membresías
        $totalMembresias = Membresia::count();

        $membresiasActivas = Membresia::where('estado', 'activa')->count();

        $membresiasResumen = Membresia::withCount('clientes')
            ->orderBy('precio')
            ->get();

        // Pagos de hoy
        $pagosHoyBase = Pago::where(function ($query) use ($hoy) {
                $query->whereDate('fecha_pago', $hoy)
                    ->orWhere(function ($subQuery) use ($hoy) {
                        $subQuery->whereNull('fecha_pago')
                            ->whereDate('created_at', $hoy);
                    });
            });

        $totalPagosHoy = (clone $pagosHoyBase)->count();

        $ingresosHoy = (clone $pagosHoyBase)
            ->where('estado', 'pagado')
            ->sum('monto');

        $ingresosEfectivo = (clone $pagosHoyBase)
            ->where('estado', 'pagado')
            ->where('metodo_pago', 'Efectivo')
            ->sum('monto');

        $ingresosTarjeta = (clone $pagosHoyBase)
            ->where('estado', 'pagado')
            ->where('metodo_pago', 'Tarjeta')
            ->sum('monto');

        $ingresosTransferencia = (clone $pagosHoyBase)
            ->where('estado', 'pagado')
            ->where('metodo_pago', 'Transferencia')
            ->sum('monto');

        // Pagos del mes
        $ingresosMes = Pago::where('estado', 'pagado')
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        $ultimosPagos = Pago::with(['cliente.membresiaPlan', 'membresia', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Asistencias de hoy
        $asistenciasHoyBase = Asistencia::where(function ($query) use ($hoy) {
                $query->whereDate('fecha_hora', $hoy)
                    ->orWhere(function ($subQuery) use ($hoy) {
                        $subQuery->whereNull('fecha_hora')
                            ->whereDate('created_at', $hoy);
                    });
            });

        $asistenciasHoy = (clone $asistenciasHoyBase)->count();

        $accesosPermitidos = (clone $asistenciasHoyBase)
            ->where('resultado', 'permitido')
            ->count();

        $accesosDenegados = (clone $asistenciasHoyBase)
            ->where('resultado', 'denegado')
            ->count();

        $ultimasAsistencias = Asistencia::with(['cliente.membresiaPlan', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('dashboard', compact(
            'totalClientes',
            'clientesActivos',
            'clientesInactivos',
            'clientesVencidos',
            'clientesPorVencer',
            'totalMembresias',
            'membresiasActivas',
            'membresiasResumen',
            'totalPagosHoy',
            'ingresosHoy',
            'ingresosMes',
            'ingresosEfectivo',
            'ingresosTarjeta',
            'ingresosTransferencia',
            'ultimosPagos',
            'asistenciasHoy',
            'accesosPermitidos',
            'accesosDenegados',
            'ultimasAsistencias'
        ));
    }
}