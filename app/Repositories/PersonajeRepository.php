<?php

namespace App\Repositories;

use App\Models\Personaje;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PersonajeRepository
{
    public function listarActivos(): LengthAwarePaginator
    {
        return Personaje::where('estado', true)
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function buscarActivo(int $id): Personaje
    {
        return Personaje::where('estado', true)->findOrFail($id);
    }

    public function buscarPorSwapiId(int $swapiId): ?Personaje
    {
        return Personaje::where('swapi_id', $swapiId)->first();
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
