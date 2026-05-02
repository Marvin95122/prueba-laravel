<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'duracion_dias',
        'beneficios',
        'estado',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}