<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClienteAccesoTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $empleado;
    private Role $rolCliente;

    protected function setUp(): void
    {
        parent::setUp();

        $rolAdmin = Role::create(['nombre' => 'Administrador', 'descripcion' => 'Administrador del sistema']);
        $rolEmpleado = Role::create(['nombre' => 'Empleado', 'descripcion' => 'Empleado del sistema']);
        $this->rolCliente = Role::create(['nombre' => 'Cliente', 'descripcion' => 'Cliente del sistema']);

        $this->admin = User::create([
            'role_id' => $rolAdmin->id,
            'username' => 'Maestro',
            'password' => Hash::make('ITCA2026'),
            'estado' => 'Activo',
        ]);

        $this->empleado = User::create([
            'role_id' => $rolEmpleado->id,
            'username' => 'Daniel',
            'password' => Hash::make('DSW22'),
            'estado' => 'Activo',
        ]);
    }

    private function datosCliente(array $extra = []): array
    {
        return array_merge([
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'documento_identidad' => '123456789',
            'telefono' => '72345678',
            'correo' => null,
            'direccion' => null,
            'username' => 'juanperez',
            'password' => 'Clave1234',
        ], $extra);
    }

    /** Crea un cliente con usuario directamente en la BD (sin pasar por el formulario). */
    private function crearClienteConAcceso(string $username = 'juanperez', string $password = 'Clave1234'): Cliente
    {
        $usuario = User::create([
            'role_id' => $this->rolCliente->id,
            'username' => $username,
            'password' => Hash::make($password),
            'estado' => 'Activo',
        ]);

        return Cliente::create([
            'usuario_id' => $usuario->id,
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'documento_identidad' => '12345678-9',
            'telefono' => '72345678',
            'estado' => 'Activo',
        ]);
    }

    public function test_administrador_crea_cliente_con_usuario_y_contrasena(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('clientes.store'), $this->datosCliente())
            ->assertRedirect(route('clientes.index'));

        $usuario = User::where('username', 'juanperez')->first();

        $this->assertNotNull($usuario);
        $this->assertSame($this->rolCliente->id, $usuario->role_id);
        $this->assertSame('Activo', $usuario->estado);
        $this->assertTrue(Hash::check('Clave1234', $usuario->password));
        $this->assertNotSame('Clave1234', $usuario->password);

        $this->assertDatabaseHas('clientes', [
            'documento_identidad' => '12345678-9',
            'usuario_id' => $usuario->id,
        ]);
    }

    public function test_empleado_tambien_puede_crear_cliente_con_acceso(): void
    {
        $this->actingAs($this->empleado);

        $this->post(route('clientes.store'), $this->datosCliente())
            ->assertRedirect(route('clientes.index'));

        $this->assertDatabaseHas('users', ['username' => 'juanperez']);
        $this->assertDatabaseHas('clientes', ['documento_identidad' => '12345678-9']);
    }

    public function test_cliente_creado_puede_iniciar_sesion(): void
    {
        $this->actingAs($this->admin);
        $this->post(route('clientes.store'), $this->datosCliente());

        auth()->logout();

        $this->post(route('login.submit'), [
            'username' => 'juanperez',
            'password' => 'Clave1234',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->esCliente());
    }

    public function test_usuario_y_contrasena_son_obligatorios_al_crear(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('clientes.store'), $this->datosCliente([
            'username' => null,
            'password' => null,
        ]))->assertSessionHasErrors(['username', 'password']);

        $this->assertDatabaseCount('clientes', 0);
    }

    public function test_contrasena_debe_tener_al_menos_ocho_caracteres(): void
    {
        $this->actingAs($this->admin);

        $this->post(route('clientes.store'), $this->datosCliente(['password' => '1234567']))
            ->assertSessionHasErrors('password');

        $this->assertDatabaseCount('clientes', 0);
        $this->assertDatabaseMissing('users', ['username' => 'juanperez']);
    }

    public function test_no_permite_un_usuario_repetido(): void
    {
        $this->actingAs($this->admin);

        // "Maestro" ya existe (es el administrador).
        $this->post(route('clientes.store'), $this->datosCliente(['username' => 'Maestro']))
            ->assertSessionHasErrors('username');

        $this->assertDatabaseCount('clientes', 0);
    }

    public function test_si_falla_la_validacion_no_se_crea_el_usuario(): void
    {
        $this->actingAs($this->admin);

        // DUI inválido: no debe quedar ningún usuario huérfano.
        $this->post(route('clientes.store'), $this->datosCliente(['documento_identidad' => 'abc']))
            ->assertSessionHasErrors('documento_identidad');

        $this->assertDatabaseMissing('users', ['username' => 'juanperez']);
    }

    public function test_administrador_puede_cambiar_la_contrasena_del_cliente(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->admin);

        $this->put(route('clientes.update', $cliente), $this->datosCliente([
            'documento_identidad' => '12345678-9',
            'password' => 'NuevaClave99',
        ]))->assertRedirect(route('clientes.index'));

        $usuario = $cliente->fresh()->usuario;

        $this->assertTrue(Hash::check('NuevaClave99', $usuario->password));
        $this->assertFalse(Hash::check('Clave1234', $usuario->password));
    }

    public function test_empleado_puede_cambiar_la_contrasena_del_cliente(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->empleado);

        $this->put(route('clientes.update', $cliente), $this->datosCliente([
            'documento_identidad' => '12345678-9',
            'password' => 'OtraClave555',
        ]))->assertRedirect(route('clientes.index'));

        $this->assertTrue(Hash::check('OtraClave555', $cliente->fresh()->usuario->password));
    }

    public function test_contrasena_vacia_al_editar_conserva_la_actual(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->admin);

        $this->put(route('clientes.update', $cliente), $this->datosCliente([
            'documento_identidad' => '12345678-9',
            'nombres' => 'Juan Carlos',
            'password' => null,
        ]))->assertRedirect(route('clientes.index'));

        $cliente->refresh();

        $this->assertSame('Juan Carlos', $cliente->nombres);
        $this->assertTrue(Hash::check('Clave1234', $cliente->usuario->password));
    }

    public function test_al_editar_se_puede_conservar_o_cambiar_el_nombre_de_usuario(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->admin);

        // Mismo usuario: no debe marcar "ya registrado" contra sí mismo.
        $this->put(route('clientes.update', $cliente), $this->datosCliente([
            'documento_identidad' => '12345678-9',
            'password' => null,
        ]))->assertSessionHasNoErrors();

        // Cambiarlo a uno nuevo.
        $this->put(route('clientes.update', $cliente), $this->datosCliente([
            'documento_identidad' => '12345678-9',
            'username' => 'juan.nuevo',
            'password' => null,
        ]))->assertSessionHasNoErrors();

        $this->assertSame('juan.nuevo', $cliente->fresh()->usuario->username);
    }

    public function test_no_se_puede_editar_con_el_usuario_de_otra_persona(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->admin);

        $this->put(route('clientes.update', $cliente), $this->datosCliente([
            'documento_identidad' => '12345678-9',
            'username' => 'Maestro',
            'password' => null,
        ]))->assertSessionHasErrors('username');

        $this->assertSame('juanperez', $cliente->fresh()->usuario->username);
    }

    public function test_cliente_antiguo_sin_usuario_puede_recibir_acceso_al_editarlo(): void
    {
        $cliente = Cliente::create([
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'estado' => 'Activo',
        ]);

        $this->actingAs($this->admin);

        $this->put(route('clientes.update', $cliente), [
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'username' => 'analopez',
            'password' => 'Clave1234',
        ])->assertRedirect(route('clientes.index'));

        $cliente->refresh();

        $this->assertNotNull($cliente->usuario_id);
        $this->assertSame('analopez', $cliente->usuario->username);
        $this->assertSame($this->rolCliente->id, $cliente->usuario->role_id);
    }

    public function test_cliente_antiguo_sin_usuario_puede_editarse_sin_crear_acceso(): void
    {
        $cliente = Cliente::create([
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'estado' => 'Activo',
        ]);

        $this->actingAs($this->admin);

        $this->put(route('clientes.update', $cliente), [
            'nombres' => 'Ana Maria',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'username' => null,
            'password' => null,
        ])->assertRedirect(route('clientes.index'));

        $this->assertNull($cliente->fresh()->usuario_id);
        $this->assertSame('Ana Maria', $cliente->fresh()->nombres);
    }

    public function test_cliente_desactivado_no_puede_iniciar_sesion_y_al_reactivarlo_si(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->admin);

        $this->patch(route('clientes.desactivar', $cliente))->assertRedirect(route('clientes.index'));

        $this->assertSame('Inactivo', $cliente->fresh()->estado);
        $this->assertSame('Inactivo', $cliente->fresh()->usuario->estado);

        auth()->logout();

        $this->post(route('login.submit'), [
            'username' => 'juanperez',
            'password' => 'Clave1234',
        ])->assertSessionHasErrors('username');

        $this->assertGuest();

        // Reactivar.
        $this->actingAs($this->admin);
        $this->patch(route('clientes.desactivar', $cliente));

        auth()->logout();

        $this->post(route('login.submit'), [
            'username' => 'juanperez',
            'password' => 'Clave1234',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticated();
    }

    public function test_no_se_modifica_un_usuario_que_no_es_cliente(): void
    {
        // Caso defensivo: un cliente vinculado por error a un empleado.
        $cliente = Cliente::create([
            'usuario_id' => $this->empleado->id,
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'estado' => 'Activo',
        ]);

        $this->actingAs($this->admin);

        $this->put(route('clientes.update', $cliente), [
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'username' => 'Daniel',
            'password' => 'Hackeada123',
        ]);

        $this->assertTrue(Hash::check('DSW22', $this->empleado->fresh()->password));
    }

    public function test_formulario_de_crear_muestra_los_campos_de_acceso(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('clientes.create'))
            ->assertOk()
            ->assertSee('Acceso al sistema')
            ->assertSee('name="username"', false)
            ->assertSee('name="password"', false);
    }

    public function test_formulario_de_editar_muestra_el_usuario_actual(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->empleado);

        $this->get(route('clientes.edit', $cliente))
            ->assertOk()
            ->assertSee('value="juanperez"', false)
            ->assertSee('Nueva contraseña');
    }

    public function test_formulario_de_editar_cliente_sin_acceso_muestra_aviso(): void
    {
        $cliente = Cliente::create([
            'nombres' => 'Ana',
            'apellidos' => 'Lopez',
            'documento_identidad' => '11111111-1',
            'estado' => 'Activo',
        ]);

        $this->actingAs($this->admin);

        $this->get(route('clientes.edit', $cliente))
            ->assertOk()
            ->assertSee('aún no tiene acceso');
    }

    public function test_listado_y_detalle_muestran_el_usuario_de_acceso(): void
    {
        $cliente = $this->crearClienteConAcceso();
        $this->actingAs($this->admin);

        $this->get(route('clientes.index'))
            ->assertOk()
            ->assertSee('juanperez');

        $this->get(route('clientes.show', $cliente))
            ->assertOk()
            ->assertSee('juanperez');
    }
}
