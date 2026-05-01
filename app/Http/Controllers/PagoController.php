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
        $clientes = Cliente::orderBy('nombre')->get();
        
        $pagosHoy = Pago::with('cliente')
                        ->whereDate('created_at', Carbon::today())
                        ->orderBy('created_at', 'desc')
                        ->get();
        
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