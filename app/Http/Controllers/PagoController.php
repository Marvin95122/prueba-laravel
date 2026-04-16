<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index()
    {
        // Traemos a los clientes para el buscador
        $clientes = Cliente::orderBy('nombre')->get();
        
        // Traemos los pagos solo del día de hoy
        $pagosHoy = Pago::with('cliente')
                        ->whereDate('created_at', Carbon::today())
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        // Calculamos el total de dinero en caja
        $totalCaja = $pagosHoy->sum('monto');

        return view('pagos.index', compact('clientes', 'pagosHoy', 'totalCaja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string',
            'monto' => 'required|numeric|min:1',
            'metodo_pago' => 'required|string'
        ]);

        Pago::create($request->all());

        return back()->with('success', '¡Pago registrado exitosamente en la caja!');
    }
}