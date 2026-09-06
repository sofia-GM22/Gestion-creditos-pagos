<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'role_id',
        'username',
        'password',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Métodos de ayuda para no repetir el nombre del rol en todos lados
    public function esAdministrador(): bool
    {
        return $this->role->nombre === 'Administrador';
    }

    public function esEmpleado(): bool
    {
        return $this->role->nombre === 'Empleado';
    }

    public function esCliente(): bool
    {
        return $this->role->nombre === 'Cliente';
    }
}