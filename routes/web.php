<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
// use App\Http\Controllers\AsistenciaController; // Se usará en el próximo paso
// use App\Http\Controllers\PagoController; // Se usará en el próximo paso
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. RUTAS PÚBLICAS (No requieren sesión)
// ==========================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/galeria', 'galeria')->name('galeria');
Route::view('/contacto', 'contacto')->name('contacto');

// ==========================================
// 2. RUTAS PROTEGIDAS (Requieren Login)
// ==========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Módulo de Perfil (Generado por Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ------------------------------------------
    // MÓDULO: CLIENTES (Protegido por Roles)
    // ------------------------------------------
    
    // Admin y Recepción: pueden ver y registrar
    Route::middleware(['role:admin,recepcion'])->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    });

    // SOLO Admin: puede editar, actualizar y eliminar
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    });

    // MÓDULOS: ASISTENCIAS Y PAGOS
    Route::resource('asistencias', App\Http\Controllers\AsistenciaController::class)->only(['index', 'store']);
    Route::resource('pagos', App\Http\Controllers\PagoController::class)->only(['index', 'store']);

});

// Carga las rutas de autenticación (Login, Registro, etc.)
require __DIR__.'/auth.php';