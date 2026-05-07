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

    public function getDiasRestantesAttribute()
    {
        if (!$this->vigencia_hasta) {
            return null;
        }

        return today()->diffInDays($this->vigencia_hasta, false);
    }

    public function getEstadoVigenciaAttribute()
    {
        if (!$this->vigencia_hasta) {
            return 'sin_vigencia';
        }

        if ($this->dias_restantes < 0) {
            return 'vencida';
        }

        if ($this->dias_restantes <= 7) {
            return 'por_vencer';
        }

        return 'vigente';
    }

    public function getClaseVigenciaAttribute()
    {
        return match ($this->estado_vigencia) {
            'vigente' => 'bg-success',
            'por_vencer' => 'bg-warning text-dark',
            'vencida' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getTextoVigenciaAttribute()
    {
        return match ($this->estado_vigencia) {
            'vigente' => 'Vigente',
            'por_vencer' => 'Por vencer',
            'vencida' => 'Vencida',
            default => 'Sin vigencia',
        };
    }

    public function getPuedeAccederAttribute()
    {
        return $this->estado === 'activa'
            && $this->membresia_id
            && $this->vigencia_hasta
            && $this->vigencia_hasta->gte(today());
    }

    public function getMotivoBloqueoAttribute()
    {
        if ($this->estado !== 'activa') {
            return 'Cliente inactivo o suspendido';
        }

        if (!$this->membresia_id) {
            return 'Sin membresía asignada';
        }

        if (!$this->vigencia_hasta) {
            return 'Sin fecha de vigencia';
        }

        if ($this->vigencia_hasta->lt(today())) {
            return 'Membresía vencida';
        }

        return 'Acceso autorizado';
    }
}