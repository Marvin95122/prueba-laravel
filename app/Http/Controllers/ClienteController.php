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
        $cliente->load('membresiaPlan');

        return view('clientes.show', compact('cliente'));
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