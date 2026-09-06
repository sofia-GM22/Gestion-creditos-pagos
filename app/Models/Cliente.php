<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id',
        'nombres',
        'apellidos',
        'documento_identidad',
        'telefono',
        'correo',
        'direccion',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function creditos()
    {
        return $this->hasMany(Credito::class, 'cliente_id');
    }

    public function scopeBuscar($query, $texto)
    {
        return $query->where(function ($q) use ($texto) {
            $q->where('nombres', 'like', "%{$texto}%")
              ->orWhere('apellidos', 'like', "%{$texto}%")
              ->orWhere('documento_identidad', 'like', "%{$texto}%");
        });
    }
}