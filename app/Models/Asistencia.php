<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id'];

    // Relación: Una asistencia pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}