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

        return view('clientes.index', compact('clientes', 'membresias'));
    }

    public function create()
    {
        $membresias = Membresia::where('estado', 'activa')
            ->orderBy('precio')
            ->get();

        return view('clientes.create', compact('membresias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:80'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'membresia_id' => ['required', 'exists:membresias,id'],
            'vigencia_hasta' => ['required', 'date'],
            'estado' => ['required', 'in:activa,inactiva'],
        ]);

        $membresia = Membresia::findOrFail($data['membresia_id']);

        // Se conserva para compatibilidad con registros anteriores.
        $data['membresia'] = strtolower($membresia->nombre);

        Cliente::create($data);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
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
        $membresias = Membresia::where('estado', 'activa')
            ->orderBy('precio')
            ->get();

        return view('clientes.edit', compact('cliente', 'membresias'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:80'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'membresia_id' => ['required', 'exists:membresias,id'],
            'vigencia_hasta' => ['required', 'date'],
            'estado' => ['required', 'in:activa,inactiva'],
        ]);

        $membresia = Membresia::findOrFail($data['membresia_id']);

        // Se conserva para compatibilidad con datos antiguos.
        $data['membresia'] = strtolower($membresia->nombre);

        $cliente->update($data);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}