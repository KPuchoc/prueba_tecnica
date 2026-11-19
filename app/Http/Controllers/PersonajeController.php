<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportarPersonajeRequest;
use App\Http\Requests\PersonajeRequest;
use App\Models\Personaje;
use App\Services\PersonajeService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PersonajeController extends Controller
{
    public function __construct(
        private PersonajeService $personajeService
    ) {}

    public function index(): Response
    {
        return Inertia::render('Personajes/Index', [
            'personajes' => $this->personajeService->listar(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Personajes/Create', [
            'generos' => Personaje::GENEROS,
        ]);
    }

    public function store(PersonajeRequest $request): RedirectResponse
    {
        $this->personajeService->crear($request->validated());

        return to_route('personajes.index')->with('success', 'Personaje creado correctamente.');
    }

    public function edit(int $id): Response
    {
        return Inertia::render('Personajes/Edit', [
            'personaje' => $this->personajeService->buscar($id),
            'generos' => Personaje::GENEROS,
        ]);
    }

    public function update(PersonajeRequest $request, int $id): RedirectResponse
    {
        $this->personajeService->actualizar($id, $request->validated());

        return to_route('personajes.index')->with('success', 'Personaje actualizado correctamente.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->personajeService->eliminar($id);

        return back()->with('success', 'Personaje eliminado correctamente.');
    }

    public function importar(ImportarPersonajeRequest $request): RedirectResponse
    {
        $personaje = $this->personajeService->importarDesdeSwapi($request->integer('swapi_id'));

        return back()->with('success', "{$personaje->nombre} importado correctamente.");
    }
}
