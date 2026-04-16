<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        // Traemos clientes activos para el registro
        $clientes = Cliente::where('estado', 'activa')->orderBy('nombre')->get();
        
        // Traemos las asistencias de HOY, cargando los datos del cliente relacionado
        $asistencias = Asistencia::with('cliente')
                        ->whereDate('created_at', Carbon::today())
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('asistencias.index', compact('clientes', 'asistencias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id'
        ]);

        Asistencia::create([
            'cliente_id' => $request->cliente_id
        ]);

        return back()->with('success', '¡Entrada registrada con éxito!');
    }
}