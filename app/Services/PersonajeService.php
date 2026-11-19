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

    public function listar(?string $buscar, ?string $genero): LengthAwarePaginator
    {
        return $this->personajeRepository->listarActivos($buscar, $genero);
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

    public function listarPersonajesSwapi(int $pagina): array
    {
        $resultado = $this->swapiService->listarPersonajes($pagina);
        $importados = $this->personajeRepository->swapiIdsImportados();

        foreach ($resultado['personajes'] as $indice => $personaje) {
            $resultado['personajes'][$indice]['importado'] = in_array($personaje['id'], $importados);
        }

        return $resultado;
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
