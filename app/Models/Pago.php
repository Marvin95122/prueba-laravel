<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'cliente_id',
        'membresia_id',
        'user_id',
        'monto',
        'monto_recibido',
        'cambio',
        'concepto',
        'tipo_pago',
        'metodo_pago',
        'referencia',
        'estado',
        'fecha_pago',
        'notas',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'monto_recibido' => 'decimal:2',
        'cambio' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function membresia()
    {
        return $this->belongsTo(Membresia::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}