<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
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

    public function test_logout_cierra_la_sesion_y_protege_el_inicio(): void
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));

        $this->assertGuest();

        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    public function test_paginas_autenticadas_no_se_pueden_guardar_en_cache(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('home'));

        $response->assertOk();

       $cacheControl = $response->headers->get('Cache-Control');

$this->assertNotNull($cacheControl);
$this->assertStringContainsString('no-store', $cacheControl);
$this->assertStringContainsString('no-cache', $cacheControl);
$this->assertStringContainsString('must-revalidate', $cacheControl);
$this->assertStringContainsString('max-age=0', $cacheControl);

$response->assertHeader('Pragma', 'no-cache');
$response->assertHeader('Expires', '0');
    }
}

