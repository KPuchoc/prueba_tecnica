<?php

use App\Http\Controllers\PersonajeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/listaPersonajes');

// Personajes
Route::get('/listaPersonajes', [PersonajeController::class, 'listarPersonajes']);
Route::get('/personaje/crear', [PersonajeController::class, 'formularioCrearPersonaje']);
Route::post('/crearPersonaje', [PersonajeController::class, 'crearPersonaje']);
Route::get('/personaje/{id}/editar', [PersonajeController::class, 'formularioEditarPersonaje'])->whereNumber('id');
Route::put('/actualizar-personaje/{id}', [PersonajeController::class, 'actualizarPersonaje'])->whereNumber('id');
Route::delete('/eliminar-personaje/{id}', [PersonajeController::class, 'eliminarPersonaje'])->whereNumber('id');
Route::get('/listaPersonajesSwapi', [PersonajeController::class, 'listarPersonajesSwapi']);
Route::post('/importarPersonaje', [PersonajeController::class, 'importarPersonaje']);
