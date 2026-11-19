<?php

namespace App\Services;

use App\Models\Personaje;
use App\Repositories\PersonajeRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class PersonajeService
{
    public function __construct(
        private PersonajeRepository $personajeRepository,
        private SwapiService $swapiService
    ) {}

    public function listar(): LengthAwarePaginator
    {
        return $this->personajeRepository->listarActivos();
    }

    public function buscar(int $id): Personaje
    {
        return $this->personajeRepository->buscarActivo($id);
    }

    public function crear(array $datos): Personaje
    {
        return $this->personajeRepository->crear($datos);
    }

    public function actualizar(int $id, array $datos): Personaje
    {
        $personaje = $this->buscar($id);
        $personaje->fill($datos);

        return $this->personajeRepository->guardar($personaje);
    }

    public function eliminar(int $id): void
    {
        $personaje = $this->buscar($id);
        $personaje->estado = false;

        $this->personajeRepository->guardar($personaje);
    }

    public function importarDesdeSwapi(int $swapiId): Personaje
    {
        $personaje = $this->personajeRepository->buscarPorSwapiId($swapiId);

        if ($personaje?->estado) {
            throw ValidationException::withMessages([
                'swapi_id' => "{$personaje->nombre} ya fue importado.",
            ]);
        }

        $datos = $this->swapiService->obtenerPersonaje($swapiId);

        if ($personaje) {
            $personaje->fill($datos);
            $personaje->estado = true;

            return $this->personajeRepository->guardar($personaje);
        }

        return $this->personajeRepository->crear($datos);
    }
}
