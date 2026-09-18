<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credito extends Model
{
    protected $table = 'creditos';

    protected $fillable = [
        'cliente_id',
        'fecha_otorgamiento',
        'monto',
        'tasa_interes',
        'plazo',
        'total_credito',
        'saldo',
        'fecha_vencimiento',
        'estado',
    ];

    protected $casts = [
        'fecha_otorgamiento' => 'date',
        'fecha_vencimiento' => 'date',
        'monto' => 'decimal:2',
        'tasa_interes' => 'decimal:2',
        'total_credito' => 'decimal:2',
        'saldo' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'credito_id')
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id');
    }

    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Marca como "Vencido" cualquier crédito Activo cuya fecha de
     * vencimiento ya pasó y todavía tiene saldo pendiente.
     * Se llama al listar/consultar créditos para mantener el estado
     * siempre actualizado sin necesidad de un job programado.
     */
    public static function actualizarVencidos(): void
    {
        static::where('estado', 'Activo')
            ->where('saldo', '>', 0)
            ->whereDate('fecha_vencimiento', '<', now()->toDateString())
            ->update(['estado' => 'Vencido']);
    }
}
