<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MembresiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\EjercicioController;


// ==========================================
// 1. RUTAS PÚBLICAS (No requieren sesión)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

//Mercado Pago
Route::get('/pagos/{pago}/mercadopago/success', [PagoController::class, 'successMercadoPago'])
    ->name('mercadopago.success');

Route::get('/pagos/{pago}/mercadopago/pending', [PagoController::class, 'pendingMercadoPago'])
    ->name('mercadopago.pending');

Route::get('/pagos/{pago}/mercadopago/failure', [PagoController::class, 'failureMercadoPago'])
    ->name('mercadopago.failure');

Route::post('/mercadopago/webhook', [PagoController::class, 'webhookMercadoPago'])
    ->name('mercadopago.webhook');

Route::view('/galeria', 'galeria')->name('galeria');
Route::view('/contacto', 'contacto')->name('contacto');

// ==========================================
// 2. RUTAS PROTEGIDAS (Requieren Login)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulo de Perfil (Generado por Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ------------------------------------------
    // MÓDULO: CLIENTES (Protegido por Roles)
    // ------------------------------------------
    
    // Admin, Gerente y Recepción: pueden ver y registrar clientes.
    Route::middleware(['auth', 'verified', 'role:admin,gerente,recepcion'])->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    });

    // Admin y Gerente: pueden editar clientes.
    Route::middleware(['auth', 'verified', 'role:admin,gerente'])->group(function () {
        Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    });

    // Solo Admin: puede eliminar clientes.
    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    });

});

Route::middleware(['auth', 'verified', 'role:admin,gerente,recepcion'])->group(function () {
    Route::resource('asistencias', AsistenciaController::class)->only(['index', 'store']);
    Route::get('/ejercicios', [EjercicioController::class, 'index'])->name('ejercicios.index');
    Route::resource('pagos', PagoController::class)->only(['index', 'store']);
    Route::get('/pagos/{pago}/ticket', [PagoController::class, 'ticket'])->name('pagos.ticket');

    // Mercado Pago
    Route::get('/pagos/{pago}/mercadopago/generar', [PagoController::class, 'generarMercadoPago'])
    ->name('mercadopago.generar');

    Route::get('/pagos/{pago}/mercadopago/checkout', [PagoController::class, 'checkoutMercadoPago'])
        ->name('mercadopago.checkout');

    Route::post('/pagos/{pago}/mercadopago/verificar', [PagoController::class, 'verificarMercadoPago'])
        ->name('mercadopago.verificar');
});

Route::middleware(['auth', 'verified', 'role:admin,gerente'])->group(function () {
    Route::resource('membresias', MembresiaController::class)->except(['destroy']);
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::delete('/membresias/{membresia}', [MembresiaController::class, 'destroy'])
        ->name('membresias.destroy');
});

Route::get('/health', function () {
    return response('OK', 200);
});
// Carga las rutas de autenticación (Login, Registro, etc.)
require __DIR__.'/auth.php';