<?php

use App\Http\Controllers\JerarquiaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TiposProductosController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\TicketsLogsController;

Route::get('/debug', fn() => response()->json(['ok' => true]));


// ORGANIZACIONES
Route::prefix('organizaciones')->group(function () {
    Route::get('/', [OrganizacionController::class, 'listar']);
    Route::post('/', [OrganizacionController::class, 'guardar']);
    Route::get('/{id}', [OrganizacionController::class, 'ver']);
    Route::put('/{id}', [OrganizacionController::class, 'actualizar']);
    Route::delete('/{id}', [OrganizacionController::class, 'eliminar']);
});

// USUARIOS
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'listar']);
    Route::post('/', [UsuarioController::class, 'guardar']);
    Route::get('/{id}', [UsuarioController::class, 'ver']);
    Route::put('/{id}', [UsuarioController::class, 'actualizar']);
    Route::delete('/{id}', [UsuarioController::class, 'eliminar']);
});

// JERARQUÍAS INICIALES
Route::prefix('jerarquia-inicial')->group(function () {
    Route::get('/', [JerarquiaController::class, 'listar']);
    Route::post('/', [JerarquiaController::class, 'guardar']);
    Route::get('/{id}', [JerarquiaController::class, 'ver']);
    Route::put('/{id}', [JerarquiaController::class, 'actualizar']);
    Route::delete('/{id}', [JerarquiaController::class, 'eliminar']);
});


// ROLES
Route::prefix('roles')->group(function () {
    Route::get('/', [RolesController::class, 'listar']);
    Route::post('/', [RolesController::class, 'guardar']);
    Route::get('/{id}', [RolesController::class, 'ver']);
    Route::put('/{id}', [RolesController::class, 'actualizar']);
    Route::delete('/{id}', [RolesController::class, 'eliminar']);
});

// TIPOS DE PRODUCTOS
Route::prefix('tipos-productos')->group(function () {
    Route::get('/', [TiposProductosController::class, 'listar']);
    Route::post('/', [TiposProductosController::class, 'guardar']);
    Route::get('/{id}', [TiposProductosController::class, 'ver']);
    Route::put('/{id}', [TiposProductosController::class, 'actualizar']);
    Route::delete('/{id}', [TiposProductosController::class, 'eliminar']);
});

// TICKETS
Route::prefix('tickets')->group(function () {
    Route::get('/', [TicketsController::class, 'listar']);
    Route::post('/', [TicketsController::class, 'guardar']);
    Route::get('/{id}', [TicketsController::class, 'ver']);
    Route::put('/{id}', [TicketsController::class, 'actualizar']);
    Route::delete('/{id}', [TicketsController::class, 'eliminar']);
});

// LOGS DE TICKETS
Route::prefix('tickets-logs')->group(function () {
    Route::get('/', [TicketsLogsController::class, 'listar']);
    Route::post('/', [TicketsLogsController::class, 'guardar']);
    Route::get('/{id}', [TicketsLogsController::class, 'ver']);
    Route::put('/{id}', [TicketsLogsController::class, 'actualizar']);
    Route::delete('/{id}', [TicketsLogsController::class, 'eliminar']);
});
