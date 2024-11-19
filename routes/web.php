<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcesoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\TareaController;

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
    Route::get('actividades/{proceso_id}/buscar', [ActividadController::class, 'buscar'])->name('actividades.buscar');
    Route::get('actividades/{proceso_id}/create', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('actividades/{proceso_id}/store', [ActividadController::class, 'Store'])->name('actividades.store');
    Route::resource('actividades', ActividadController::class)->except(['index','create','store']);

    Route::get('tareas/{actividad_id}', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('tareas/{actividad_id}/buscar', [TareaController::class, 'buscar'])->name('tareas.buscar');
    Route::get('tareas/{actividad_id}/create', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('tareas/{actividad_id}/store', [TareaController::class, 'store'])->name('tareas.store');
    Route::post('tareas/{tarea_id}/finish', [TareaController::class, 'finish'])->name('tareas.finish');
    Route::resource('tareas', TareaController::class)->except(['index','create','store']);
    
});
