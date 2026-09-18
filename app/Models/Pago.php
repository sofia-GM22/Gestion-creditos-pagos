<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'credito_id',
        'fecha_pago',
        'monto',
        'referencia',
        'observaciones',
    ];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function credito()
    {
        return $this->belongsTo(Credito::class, 'credito_id');
    }
}
