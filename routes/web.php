<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'mostrarFormulario'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'role:Administrador,Empleado'])->group(function () {
    Route::get('clientes/por-estado-credito', [ClienteController::class, 'porEstadoCredito'])
        ->name('clientes.por-estado-credito');

    Route::resource('clientes', ClienteController::class)->except(['destroy']);

    Route::patch('clientes/{cliente}/desactivar', [ClienteController::class, 'desactivar'])
        ->name('clientes.desactivar');
});