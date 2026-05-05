<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('membresiaPlan')
            ->orderBy('nombre')
            ->get();

        $asistencias = Asistencia::with(['cliente.membresiaPlan', 'user'])
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $permitidas = $asistencias->where('resultado', 'permitido')->count();
        $denegadas = $asistencias->where('resultado', 'denegado')->count();
        $totalHoy = $asistencias->count();

        $clientesVencidos = Cliente::where('estado', 'activa')
            ->whereDate('vigencia_hasta', '<', Carbon::today())
            ->count();

        return view('asistencias.index', compact(
            'clientes',
            'asistencias',
            'permitidas',
            'denegadas',
            'totalHoy',
            'clientesVencidos'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
        ]);

        $cliente = Cliente::with('membresiaPlan')->findOrFail($request->cliente_id);

        $resultado = 'permitido';
        $motivo = 'Acceso autorizado';

        if ($cliente->estado !== 'activa') {
            $resultado = 'denegado';
            $motivo = 'Cliente inactivo o suspendido';
        } elseif (!$cliente->vigencia_hasta) {
            $resultado = 'denegado';
            $motivo = 'Cliente sin fecha de vigencia';
        } elseif ($cliente->vigencia_hasta->lt(today())) {
            $resultado = 'denegado';
            $motivo = 'Membresía vencida';
        } elseif (!$cliente->membresia_id && !$cliente->membresia) {
            $resultado = 'denegado';
            $motivo = 'Cliente sin membresía asignada';
        }

        /*
        * Solo bloquea duplicados si el acceso actual sería permitido.
        * Así, si antes fue denegado por membresía vencida y luego se renueva,
        * el cliente sí podrá entrar.
        */
        if ($resultado === 'permitido') {
            $entradaReciente = Asistencia::where('cliente_id', $cliente->id)
                ->where('resultado', 'permitido')
                ->where('created_at', '>=', now()->subMinutes(60))
                ->exists();

            if ($entradaReciente) {
                return back()->with('warning', 'Este cliente ya tiene una entrada permitida registrada recientemente.');
            }
        }

        Asistencia::create([
            'cliente_id' => $cliente->id,
            'user_id' => auth()->id(),
            'resultado' => $resultado,
            'motivo' => $motivo,
            'fecha_hora' => now(),
        ]);

        if ($resultado === 'permitido') {
            return back()->with('success', 'Acceso permitido para ' . $cliente->nombre . '.');
        }

        return back()->with('error', 'Acceso denegado para ' . $cliente->nombre . '. Motivo: ' . $motivo);
    }
}