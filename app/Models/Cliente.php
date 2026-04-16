<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
  'nombre','telefono','membresia','vigencia_hasta','estado'
];

public function asistencias() {
    return $this->hasMany(Asistencia::class);
}

public function pagos() {
    return $this->hasMany(Pago::class);
}

}

