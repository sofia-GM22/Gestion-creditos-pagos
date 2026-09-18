<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Pago;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreditoPagoTest extends TestCase
{
    use RefreshDatabase;

    private Role $administrador;
    private Role $clienteRole;
    private User $admin;
    private User $clienteUsuario;
    private Cliente $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = Role::create([
            'nombre' => 'Administrador',
            'descripcion' => 'Administrador del sistema',
        ]);

        $this->clienteRole = Role::create([
            'nombre' => 'Cliente',
            'descripcion' => 'Cliente del sistema',
        ]);

        $this->admin = User::create([
            'role_id' => $this->administrador->id,
            'username' => 'Maestro',
            'password' => Hash::make('ITCA2026'),
            'estado' => 'Activo',
        ]);

        $this->clienteUsuario = User::create([
            'role_id' => $this->clienteRole->id,
            'username' => 'Sofia',
            'password' => Hash::make('Gomez22'),
            'estado' => 'Activo',
        ]);

        $this->cliente = Cliente::create([
            'usuario_id' => $this->clienteUsuario->id,
            'nombres' => 'Sofia',
            'apellidos' => 'Gomez',
            'documento_identidad' => '00000000-0',
            'telefono' => null,
            'correo' => null,
            'direccion' => null,
            'estado' => 'Activo',
        ]);
    }

    public function test_usuario_puede_registrar_un_credito_con_total_y_saldo_calculados(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('creditos.store'), [
            'cliente_id' => $this->cliente->id,
            'fecha_otorgamiento' => '2026-09-17',
            'monto' => 1000,
            'tasa_interes' => 10,
            'plazo' => 12,
        ]);

        $response->assertRedirect();

        $credito = Credito::where('cliente_id', $this->cliente->id)
            ->where('monto', 1000)
            ->first();

        $this->assertNotNull($credito);
        $this->assertSame('10.00', $credito->tasa_interes);
        $this->assertSame(12, $credito->plazo);
        $this->assertSame('1100.00', $credito->total_credito);
        $this->assertSame('1100.00', $credito->saldo);
        $this->assertSame('Activo', $credito->estado);
        $this->assertSame(
            '2027-09-17',
            $credito->fecha_vencimiento->format('Y-m-d')
        );
    }

    public function test_pago_parcial_actualiza_el_saldo(): void
    {
        $this->actingAs($this->admin);

        $credito = Credito::create([
            'cliente_id' => $this->cliente->id,
            'fecha_otorgamiento' => '2026-09-17',
            'monto' => 1000,
            'tasa_interes' => 10,
            'plazo' => 12,
            'total_credito' => 1100,
            'saldo' => 1100,
            'fecha_vencimiento' => '2027-09-17',
            'estado' => 'Activo',
        ]);

        $response = $this->post(
            route('pagos.store', $credito),
            [
                'fecha_pago' => '2026-09-17',
                'monto' => 200,
                'referencia' => 'TEST-001',
                'observaciones' => 'Pago de prueba',
            ]
        );

        $response->assertRedirect();

        $credito->refresh();

        $this->assertSame('900.00', $credito->saldo);
        $this->assertSame('Activo', $credito->estado);

        $this->assertDatabaseHas('pagos', [
            'credito_id' => $credito->id,
            'monto' => 200.00,
            'referencia' => 'TEST-001',
        ]);
    }

    public function test_pago_completo_deja_saldo_en_cero_y_cambia_estado_a_pagado(): void
    {
        $this->actingAs($this->admin);

        $credito = Credito::create([
            'cliente_id' => $this->cliente->id,
            'fecha_otorgamiento' => '2026-09-17',
            'monto' => 500,
            'tasa_interes' => 10,
            'plazo' => 12,
            'total_credito' => 550,
            'saldo' => 550,
            'fecha_vencimiento' => '2027-09-17',
            'estado' => 'Activo',
        ]);

        $response = $this->post(
            route('pagos.store', $credito),
            [
                'fecha_pago' => '2026-09-17',
                'monto' => 550,
                'referencia' => 'TEST-PAGO-COMPLETO',
            ]
        );

        $response->assertRedirect();

        $credito->refresh();

        $this->assertSame('0.00', $credito->saldo);
        $this->assertSame('Pagado', $credito->estado);

        $this->assertDatabaseHas('pagos', [
            'credito_id' => $credito->id,
            'monto' => 550.00,
        ]);
    }

    public function test_no_permite_pagar_mas_que_el_saldo(): void
    {
        $this->actingAs($this->admin);

        $credito = Credito::create([
            'cliente_id' => $this->cliente->id,
            'fecha_otorgamiento' => '2026-09-17',
            'monto' => 500,
            'tasa_interes' => 10,
            'plazo' => 12,
            'total_credito' => 550,
            'saldo' => 550,
            'fecha_vencimiento' => '2027-09-17',
            'estado' => 'Activo',
        ]);

        $response = $this->from(
            route('pagos.create', $credito)
        )->post(
            route('pagos.store', $credito),
            [
                'fecha_pago' => '2026-09-17',
                'monto' => 551,
                'referencia' => 'TEST-EXCESO',
            ]
        );

        $response->assertRedirect(route('pagos.create', $credito));
        $response->assertSessionHasErrors('monto');

        $credito->refresh();

        $this->assertSame('550.00', $credito->saldo);

        $this->assertDatabaseMissing('pagos', [
            'credito_id' => $credito->id,
            'monto' => 551.00,
        ]);
    }

    public function test_credito_vencido_pasa_a_estado_vencido(): void
    {
        $this->actingAs($this->admin);

        $credito = Credito::create([
            'cliente_id' => $this->cliente->id,
            'fecha_otorgamiento' => '2025-01-01',
            'monto' => 500,
            'tasa_interes' => 10,
            'plazo' => 1,
            'total_credito' => 550,
            'saldo' => 550,
            'fecha_vencimiento' => '2025-02-01',
            'estado' => 'Activo',
        ]);

        $response = $this->get(route('creditos.show', $credito));

        $response->assertOk();

        $credito->refresh();

        $this->assertSame('Vencido', $credito->estado);
    }

    public function test_cliente_no_puede_ver_credito_de_otro_cliente(): void
    {
        $otroUsuario = User::create([
            'role_id' => $this->clienteRole->id,
            'username' => 'Carlos',
            'password' => Hash::make('Prueba123'),
            'estado' => 'Activo',
        ]);

        $otroCliente = Cliente::create([
            'usuario_id' => $otroUsuario->id,
            'nombres' => 'Carlos',
            'apellidos' => 'Prueba',
            'documento_identidad' => '22222222-2',
            'telefono' => null,
            'correo' => null,
            'direccion' => null,
            'estado' => 'Activo',
        ]);

        $credito = Credito::create([
            'cliente_id' => $otroCliente->id,
            'fecha_otorgamiento' => '2026-09-17',
            'monto' => 300,
            'tasa_interes' => 10,
            'plazo' => 6,
            'total_credito' => 330,
            'saldo' => 330,
            'fecha_vencimiento' => '2027-03-17',
            'estado' => 'Activo',
        ]);

        $this->actingAs($this->clienteUsuario);

        $response = $this->get(route('creditos.show', $credito));

        $response->assertForbidden();
    }
}