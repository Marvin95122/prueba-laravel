<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('id', 'desc')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','min:3','max:80'],
            'telefono' => ['nullable','string','max:15'],
            'membresia' => ['required','in:basica,plus,premium'],
            'vigencia_hasta' => ['required','date'],
            'estado' => ['required','in:activa,inactiva'],
        ]);

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('ok', 'Cliente registrado.');
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre' => ['required','string','min:3','max:80'],
            'telefono' => ['nullable','string','max:15'],
            'membresia' => ['required','in:basica,plus,premium'],
            'vigencia_hasta' => ['required','date'],
            'estado' => ['required','in:activa,inactiva'],
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('ok', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('ok', 'Cliente eliminado.');
    }
}