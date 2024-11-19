<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ActividadController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('procesos', ProcesoController::class);
    Route::get('actividades/{proceso_id}', [ActividadController::class, 'index'])->name('actividades.index');
    Route::get('actividades/{proceso_id}/create', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('actividades/{proceso_id}/store', [ActividadController::class, 'Store'])->name('actividades.store');
    Route::resource('actividades', ActividadController::class)->except(['index','create','store']);
    
});
