<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EmpleadoController;


Route::get('/login', [AuthController::class, 'mostrarFormulario'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('home');
})->middleware(['auth', 'no.cache'])->name('home');

Route::middleware([
    'auth',
    'no.cache',
    'role:Administrador,Empleado'
])->group(function () {
    Route::get('clientes/por-estado-credito', [ClienteController::class, 'porEstadoCredito'])
        ->name('clientes.por-estado-credito');

    Route::resource('clientes', ClienteController::class)->except(['destroy']);

    Route::patch('clientes/{cliente}/desactivar', [ClienteController::class, 'desactivar'])
        ->name('clientes.desactivar');

    // Panel de créditos (registrar, listar, filtrar por estado)
    Route::get('creditos', [CreditoController::class, 'index'])->name('creditos.index');
    Route::get('creditos/crear', [CreditoController::class, 'create'])->name('creditos.create');
    Route::post('creditos', [CreditoController::class, 'store'])->name('creditos.store');

    // Registrar un pago sobre un crédito específico
    Route::get('creditos/{credito}/pagos/crear', [PagoController::class, 'create'])->name('pagos.create');
    Route::post('creditos/{credito}/pagos', [PagoController::class, 'store'])->name('pagos.store');

    // Historial general de pagos (BL-34)
    Route::get('pagos', [PagoController::class, 'index'])->name('pagos.index');
});

// Gestión exclusiva de empleados para Administradores
Route::middleware([
    'auth',
    'no.cache',
    'role:Administrador',
])->group(function () {
    Route::get('empleados', [EmpleadoController::class, 'index'])
        ->name('empleados.index');

    Route::get('empleados/crear', [EmpleadoController::class, 'create'])
        ->name('empleados.create');

    Route::post('empleados', [EmpleadoController::class, 'store'])
        ->name('empleados.store');

    Route::get('empleados/{empleado}/editar', [EmpleadoController::class, 'edit'])
        ->name('empleados.edit');

    Route::put('empleados/{empleado}', [EmpleadoController::class, 'update'])
        ->name('empleados.update');

    Route::patch('empleados/{empleado}/desactivar', [EmpleadoController::class, 'desactivar'])
        ->name('empleados.desactivar');
});



// Detalle de crédito y comprobante de pago: accesibles para cualquier
// usuario autenticado, pero el controlador valida que un Cliente solo
// pueda ver sus propios créditos y pagos.
Route::middleware(['auth', 'no.cache'])->group(function () {
    Route::get('creditos/{credito}', [CreditoController::class, 'show'])->name('creditos.show');
    Route::get('pagos/{pago}', [PagoController::class, 'show'])->name('pagos.show');
});

// Vistas exclusivas del rol Cliente
Route::middleware([
    'auth',
    'no.cache',
    'role:Cliente'
])->group(function () {
    Route::get('mis-creditos', [CreditoController::class, 'misCreditos'])->name('creditos.mios');
    Route::get('mis-pagos', [PagoController::class, 'misPagos'])->name('pagos.mios');
});