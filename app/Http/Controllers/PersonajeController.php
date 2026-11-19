<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarPersonajeRequest;
use App\Http\Requests\PersonajeRequest;
use App\Models\Personaje;
use App\Services\PersonajeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PersonajeController extends Controller
{
    public function __construct(
        private PersonajeService $personajeService
    ) {}

    public function listarPersonajes(Request $request): Response
    {
        return Inertia::render('Personajes/Index', [
            'personajes' => $this->personajeService->listar($request->input('buscar'), $request->input('genero')),
            'filtros' => $request->only('buscar', 'genero'),
            'generos' => Personaje::GENEROS,
        ]);
    }

    public function formularioCrearPersonaje(): Response
    {
        return Inertia::render('Personajes/Create', [
            'generos' => Personaje::GENEROS,
        ]);
    }

    public function crearPersonaje(PersonajeRequest $request): RedirectResponse
    {
        $this->personajeService->crear($request->validated());

        return redirect('/listaPersonajes')->with('success', 'Personaje creado correctamente.');
    }

    public function formularioEditarPersonaje(int $id): Response
    {
        return Inertia::render('Personajes/Edit', [
            'personaje' => $this->personajeService->buscar($id),
            'generos' => Personaje::GENEROS,
        ]);
    }

    public function actualizarPersonaje(PersonajeRequest $request, int $id): RedirectResponse
    {
        $this->personajeService->actualizar($id, $request->validated());

        return redirect('/listaPersonajes')->with('success', 'Personaje actualizado correctamente.');
    }

    public function eliminarPersonaje(int $id): RedirectResponse
    {
        $this->personajeService->eliminar($id);

        return back()->with('success', 'Personaje eliminado correctamente.');
    }

    public function listarPersonajesSwapi(Request $request): JsonResponse
    {
        return response()->json($this->personajeService->listarPersonajesSwapi($request->integer('pagina', 1)));
    }

    public function importarPersonaje(ImportarPersonajeRequest $request): RedirectResponse
    {
        $personaje = $this->personajeService->importarDesdeSwapi($request->integer('swapi_id'));

        return back()->with('success', "{$personaje->nombre} importado correctamente.");
    }
}
