<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'membresia_id',
        'membresia',
        'vigencia_hasta',
        'estado',
    ];

    protected $casts = [
        'vigencia_hasta' => 'date',
    ];

    public function membresiaPlan()
    {
        return $this->belongsTo(Membresia::class, 'membresia_id');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function getNombreMembresiaAttribute()
    {
        return $this->membresiaPlan?->nombre ?? ucfirst($this->membresia ?? 'Sin membresía');
    }

    public function getMembresiaVencidaAttribute()
    {
        if (!$this->vigencia_hasta) {
            return true;
        }

        return $this->vigencia_hasta->lt(today());
    }
}