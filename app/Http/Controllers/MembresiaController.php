<?php

namespace App\Http\Controllers;

use App\Models\Membresia;
use Illuminate\Http\Request;

class MembresiaController extends Controller
{
    public function index()
    {
        $membresias = Membresia::orderBy('id', 'desc')->paginate(10);
        return view('membresias.index', compact('membresias'));
    }

    public function create()
    {
        return view('membresias.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:80', 'unique:membresias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio' => ['required', 'numeric', 'min:1'],
            'duracion_dias' => ['required', 'integer', 'min:1'],
            'beneficios' => ['nullable', 'string', 'max:800'],
            'estado' => ['required', 'in:activa,inactiva'],
        ]);

        Membresia::create($data);

        return redirect()
            ->route('membresias.index')
            ->with('success', 'Membresía registrada correctamente.');
    }

    public function show(Membresia $membresia)
    {
        return view('membresias.show', compact('membresia'));
    }

    public function edit(Membresia $membresia)
    {
        return view('membresias.edit', compact('membresia'));
    }

    public function update(Request $request, Membresia $membresia)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:80', 'unique:membresias,nombre,' . $membresia->id],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio' => ['required', 'numeric', 'min:1'],
            'duracion_dias' => ['required', 'integer', 'min:1'],
            'beneficios' => ['nullable', 'string', 'max:800'],
            'estado' => ['required', 'in:activa,inactiva'],
        ]);

        $membresia->update($data);

        return redirect()
            ->route('membresias.index')
            ->with('success', 'Membresía actualizada correctamente.');
    }

    public function destroy(Membresia $membresia)
    {
        if ($membresia->clientes()->count() > 0) {
            return redirect()
                ->route('membresias.index')
                ->with('error', 'No se puede eliminar esta membresía porque tiene clientes asignados.');
        }

        $membresia->delete();

        return redirect()
            ->route('membresias.index')
            ->with('success', 'Membresía eliminada correctamente.');
    }
}