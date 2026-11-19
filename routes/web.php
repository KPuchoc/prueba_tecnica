<?php

use App\Http\Controllers\PersonajeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/personajes');

Route::controller(PersonajeController::class)->whereNumber('id')->group(function () {
    Route::get('/personajes', 'index')->name('personajes.index');
    Route::get('/personajes/crear', 'create')->name('personajes.create');
    Route::post('/personajes', 'store')->name('personajes.store');
    Route::get('/personajes/{id}/editar', 'edit')->name('personajes.edit');
    Route::put('/personajes/{id}', 'update')->name('personajes.update');
    Route::delete('/personajes/{id}', 'destroy')->name('personajes.destroy');
    Route::post('/personajes/importar', 'importar')->name('personajes.importar');
});
