<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $administrador = Role::updateOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Administrador del sistema']
        );

        $empleado = Role::updateOrCreate(
            ['nombre' => 'Empleado'],
            ['descripcion' => 'Empleado del sistema']
        );

        $rolCliente = Role::updateOrCreate(
            ['nombre' => 'Cliente'],
            ['descripcion' => 'Cliente del sistema']
        );

        User::updateOrCreate(
            ['username' => 'Maestro'],
            [
                'role_id' => $administrador->id,
                'password' => Hash::make('ITCA2026'),
                'estado' => 'Activo',
            ]
        );

        User::updateOrCreate(
            ['username' => 'Daniel'],
            [
                'role_id' => $empleado->id,
                'password' => Hash::make('DSW22'),
                'estado' => 'Activo',
            ]
        );

        $sofia = User::updateOrCreate(
            ['username' => 'Sofia'],
            [
                'role_id' => $rolCliente->id,
                'password' => Hash::make('Gomez22'),
                'estado' => 'Activo',
            ]
        );

        Cliente::updateOrCreate(
            ['usuario_id' => $sofia->id],
            [
                'nombres' => 'Sofia',
                'apellidos' => 'Gomez',
                'documento_identidad' => '00000000-0',
                'telefono' => null,
                'correo' => null,
                'direccion' => null,
                'estado' => 'Activo',
            ]
        );
    }
}