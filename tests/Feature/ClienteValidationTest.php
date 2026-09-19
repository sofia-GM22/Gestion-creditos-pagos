<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClienteValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $administrador = Role::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Administrador del sistema',
        ]);

        $this->admin = User::create([
            'role_id' => $administrador->id,
            'username' => 'Maestro',
            'password' => Hash::make('ITCA2026'),
            'estado' => 'Activo',
        ]);
    }

    public function test_dui_de_nueve_digitos_se_formatea_automaticamente(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('clientes.store'), [
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'documento_identidad' => '123456789',
            'telefono' => '72345678',
            'correo' => null,
            'direccion' => null,
        ]);

        $response->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('clientes', [
            'documento_identidad' => '12345678-9',
            'telefono' => '72345678',
        ]);
    }

    public function test_rechaza_dui_con_letras_y_telefono_con_letras(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('clientes.store'), [
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'documento_identidad' => 'AS123 A123',
            'telefono' => 'abcdef',
            'correo' => null,
            'direccion' => null,
        ]);

        $response->assertSessionHasErrors([
            'documento_identidad',
            'telefono',
        ]);

        $this->assertDatabaseMissing('clientes', [
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
        ]);
    }

    public function test_telefono_debe_tener_exactamente_ocho_digitos(): void
{
    $this->actingAs($this->admin);

    $response = $this->post(route('clientes.store'), [
        'nombres' => 'Juan',
        'apellidos' => 'Perez',
        'documento_identidad' => '123456789',
        'telefono' => '1234567',
        'correo' => null,
        'direccion' => null,
    ]);

    $response->assertSessionHasErrors('telefono');

    $response = $this->post(route('clientes.store'), [
        'nombres' => 'Juan',
        'apellidos' => 'Perez',
        'documento_identidad' => '987654321',
        'telefono' => '21212828',
        'correo' => null,
        'direccion' => null,
    ]);

    $response->assertRedirect(route('clientes.index'));

    $this->assertDatabaseHas('clientes', [
        'documento_identidad' => '98765432-1',
        'telefono' => '21212828',
    ]);
}
}