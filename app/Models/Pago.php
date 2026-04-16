<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'monto', 'concepto', 'metodo_pago'];

    // Relación: Un pago pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}