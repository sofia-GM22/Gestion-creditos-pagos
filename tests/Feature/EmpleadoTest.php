<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmpleadoTest extends TestCase
{
    use RefreshDatabase;

    private function crearRoles(): array
    {
        $administrador = Role::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Administrador del sistema',
        ]);

        $empleado = Role::create([
            'nombre' => 'Empleado',
            'descripcion' => 'Empleado del sistema',
        ]);

        $cliente = Role::create([
            'nombre' => 'Cliente',
            'descripcion' => 'Cliente del sistema',
        ]);

        return compact('administrador', 'empleado', 'cliente');
    }

    private function crearAdministrador(Role $rol): User
    {
        return User::create([
            'role_id' => $rol->id,
            'username' => 'admin_test',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);
    }

    public function test_administrador_puede_ver_lista_de_empleados(): void
    {
        $roles = $this->crearRoles();
        $admin = $this->crearAdministrador($roles['administrador']);

        User::create([
            'role_id' => $roles['empleado']->id,
            'username' => 'empleado_test',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('empleados.index'));

        $response->assertOk();
        $response->assertSee('empleado_test');
    }

    public function test_administrador_puede_crear_empleado(): void
    {
        $roles = $this->crearRoles();
        $admin = $this->crearAdministrador($roles['administrador']);

        $response = $this->actingAs($admin)
            ->post(route('empleados.store'), [
                'username' => 'nuevo_empleado',
                'password' => 'password123',
            ]);

        $response->assertRedirect(route('empleados.index'));

        $empleado = User::where('username', 'nuevo_empleado')->first();

        $this->assertNotNull($empleado);
        $this->assertSame($roles['empleado']->id, $empleado->role_id);
        $this->assertSame('Activo', $empleado->estado);
        $this->assertTrue(Hash::check('password123', $empleado->password));
    }

    public function test_no_permite_usuario_repetido(): void
    {
        $roles = $this->crearRoles();
        $admin = $this->crearAdministrador($roles['administrador']);

        User::create([
            'role_id' => $roles['empleado']->id,
            'username' => 'empleado_existente',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('empleados.create'))
            ->post(route('empleados.store'), [
                'username' => 'empleado_existente',
                'password' => 'password123',
            ]);

        $response->assertSessionHasErrors([
            'username' => 'Ese nombre de usuario ya está registrado.',
        ]);
    }

    public function test_administrador_puede_editar_usuario_y_contrasena(): void
    {
        $roles = $this->crearRoles();
        $admin = $this->crearAdministrador($roles['administrador']);

        $empleado = User::create([
            'role_id' => $roles['empleado']->id,
            'username' => 'empleado_original',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('empleados.update', $empleado), [
                'username' => 'empleado_editado',
                'password' => 'nuevaClave123',
                'estado' => 'Activo',
            ]);

        $response->assertRedirect(route('empleados.index'));

        $empleado->refresh();

        $this->assertSame('empleado_editado', $empleado->username);
        $this->assertTrue(Hash::check('nuevaClave123', $empleado->password));
        $this->assertSame($roles['empleado']->id, $empleado->role_id);
    }

    public function test_contrasena_vacia_conserva_la_existente(): void
    {
        $roles = $this->crearRoles();
        $admin = $this->crearAdministrador($roles['administrador']);

        $empleado = User::create([
            'role_id' => $roles['empleado']->id,
            'username' => 'empleado_original',
            'password' => Hash::make('password_original'),
            'estado' => 'Activo',
        ]);

        $contrasenaOriginal = $empleado->password;

        $response = $this->actingAs($admin)
            ->put(route('empleados.update', $empleado), [
                'username' => 'empleado_actualizado',
                'password' => '',
                'estado' => 'Activo',
            ]);

        $response->assertRedirect(route('empleados.index'));

        $empleado->refresh();

        $this->assertSame('empleado_actualizado', $empleado->username);
        $this->assertSame($contrasenaOriginal, $empleado->password);
    }

    public function test_administrador_puede_activar_y_desactivar_empleado(): void
    {
        $roles = $this->crearRoles();
        $admin = $this->crearAdministrador($roles['administrador']);

        $empleado = User::create([
            'role_id' => $roles['empleado']->id,
            'username' => 'empleado_estado',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('empleados.desactivar', $empleado));

        $response->assertRedirect(route('empleados.index'));

        $empleado->refresh();

        $this->assertSame('Inactivo', $empleado->estado);

        $response = $this->actingAs($admin)
            ->patch(route('empleados.desactivar', $empleado));

        $response->assertRedirect(route('empleados.index'));

        $empleado->refresh();

        $this->assertSame('Activo', $empleado->estado);
    }

    public function test_empleado_no_puede_acceder_al_modulo_de_empleados(): void
    {
        $roles = $this->crearRoles();

        $empleado = User::create([
            'role_id' => $roles['empleado']->id,
            'username' => 'empleado_test',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);

        $response = $this->actingAs($empleado)
            ->get(route('empleados.index'));

        $response->assertForbidden();
    }

    public function test_cliente_no_puede_acceder_al_modulo_de_empleados(): void
    {
        $roles = $this->crearRoles();

        $cliente = User::create([
            'role_id' => $roles['cliente']->id,
            'username' => 'cliente_test',
            'password' => Hash::make('password123'),
            'estado' => 'Activo',
        ]);

        $response = $this->actingAs($cliente)
            ->get(route('empleados.index'));

        $response->assertForbidden();
    }

    public function test_usuario_no_autenticado_es_redirigido_al_login(): void
    {
        $this->crearRoles();

        $response = $this->get(route('empleados.index'));

        $response->assertRedirect(route('login'));
    }
}