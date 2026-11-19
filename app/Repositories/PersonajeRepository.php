<?php

namespace App\Repositories;

use App\Models\Personaje;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PersonajeRepository
{
    public function listarActivos(?string $buscar, ?string $genero): LengthAwarePaginator
    {
        return Personaje::where('estado', true)
            ->when($buscar, fn ($consulta) => $consulta->where(
                fn ($consulta) => $consulta->where('id', $buscar)->orWhere('nombre', 'like', "%{$buscar}%")
            ))
            ->when($genero, fn ($consulta) => $consulta->where('genero', $genero))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
    }

    public function buscarActivo(int $id): Personaje
    {
        return Personaje::where('estado', true)->findOrFail($id);
    }

    public function buscarPorSwapiId(int $swapiId): ?Personaje
    {
        return Personaje::where('swapi_id', $swapiId)->first();
    }

    public function swapiIdsImportados(): array
    {
        return Personaje::where('estado', true)
            ->whereNotNull('swapi_id')
            ->pluck('swapi_id')
            ->all();
    }

    public function crear(array $datos): Personaje
    {
        return Personaje::create($datos);
    }

    public function guardar(Personaje $personaje): Personaje
    {
        $personaje->save();

        return $personaje;
    }
}
