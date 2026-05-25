<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Membresia;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::with('membresiaPlan')
            ->when($request->buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('telefono', 'like', "%{$buscar}%");
                });
            })
            ->when($request->estado, function ($query, $estado) {
                $query->where('estado', $estado);
            })
            ->when($request->membresia_id, function ($query, $membresiaId) {
                $query->where('membresia_id', $membresiaId);
            })
            ->when($request->vigencia, function ($query, $vigencia) {
                if ($vigencia === 'sin_membresia') {
                    $query->whereNull('membresia_id');
                }

                if ($vigencia === 'vigente') {
                    $query->whereNotNull('vigencia_hasta')
                        ->whereDate('vigencia_hasta', '>=', today());
                }

                if ($vigencia === 'vencida') {
                    $query->whereNotNull('vigencia_hasta')
                        ->whereDate('vigencia_hasta', '<', today());
                }

                if ($vigencia === 'por_vencer') {
                    $query->whereNotNull('vigencia_hasta')
                        ->whereBetween('vigencia_hasta', [
                            today()->toDateString(),
                            today()->addDays(7)->toDateString(),
                        ]);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $membresias = Membresia::where('estado', 'activa')
            ->orderBy('precio')
            ->get();

        $clientesVencidosAlerta = Cliente::whereNotNull('vigencia_hasta')
            ->whereDate('vigencia_hasta', '<', today())
            ->count();

        $clientesPorVencerAlerta = Cliente::where('estado', 'activa')
            ->whereNotNull('vigencia_hasta')
            ->whereBetween('vigencia_hasta', [
                today()->toDateString(),
                today()->addDays(7)->toDateString(),
            ])
            ->count();

        $clientesInactivosAlerta = Cliente::where('estado', '!=', 'activa')->count();

        return view('clientes.index', compact(
            'clientes',
            'membresias',
            'clientesVencidosAlerta',
            'clientesPorVencerAlerta',
            'clientesInactivosAlerta'
        ));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:80'],
            'telefono' => ['nullable', 'string', 'max:15'],
        ]);

        Cliente::create([
            'nombre' => $data['nombre'],
            'telefono' => $data['telefono'] ?? null,
            'membresia_id' => null,
            'membresia' => null,
            'vigencia_hasta' => null,
            'estado' => 'inactiva',
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente. Queda pendiente de pago para activar su membresía.');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load([
            'membresiaPlan',
            'pagos' => function ($query) {
                $query->with(['membresia', 'user'])
                    ->orderBy('created_at', 'desc');
            },
            'asistencias' => function ($query) {
                $query->with('user')
                    ->orderBy('created_at', 'desc');
            },
        ]);

        $totalPagado = $cliente->pagos
            ->where('estado', 'pagado')
            ->sum('monto');

        $totalPagos = $cliente->pagos->count();
        $totalAsistencias = $cliente->asistencias->count();

        $accesosPermitidos = $cliente->asistencias
            ->where('resultado', 'permitido')
            ->count();

        $accesosDenegados = $cliente->asistencias
            ->where('resultado', 'denegado')
            ->count();

        $diasRestantes = null;

        if ($cliente->vigencia_hasta) {
            $diasRestantes = today()->diffInDays($cliente->vigencia_hasta, false);
        }

        return view('clientes.show', compact(
            'cliente',
            'totalPagado',
            'totalPagos',
            'totalAsistencias',
            'accesosPermitidos',
            'accesosDenegados',
            'diasRestantes'
        ));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:80'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'estado' => ['required', 'in:activa,inactiva'],
        ]);

        $cliente->update($data);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Datos del cliente actualizados correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}