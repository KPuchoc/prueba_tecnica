<?php

namespace App\Services;

use App\Models\Personaje;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class SwapiService
{
    private const GENEROS = [
        'male' => 'masculino',
        'female' => 'femenino',
    ];

    public function listarPersonajes(int $pagina): array
    {
        $datos = $this->consultar('/people/', ['page' => $pagina], 'No existe esa página en SWAPI.');

        $personajes = collect($datos['results'])
            ->map(fn ($personaje) => [
                'id' => (int) basename($personaje['url']),
                'nombre' => $personaje['name'],
            ])
            ->filter(fn ($personaje) => $personaje['id'] <= 83)
            ->values()
            ->all();

        return [
            'personajes' => $personajes,
            'hay_siguiente' => $datos['next'] !== null,
        ];
    }

    public function obtenerPersonaje(int $id): array
    {
        $datos = $this->consultar("/people/{$id}/", [], "No existe un personaje con el ID {$id} en SWAPI.");

        return [
            'nombre' => $datos['name'],
            'altura' => $this->convertirNumero($datos['height'], Personaje::ALTURA_MAXIMA),
            'masa' => $this->convertirNumero($datos['mass'], Personaje::MASA_MAXIMA),
            'color_cabello' => $datos['hair_color'],
            'color_ojos' => $datos['eye_color'],
            'genero' => self::GENEROS[$datos['gender']] ?? 'n/a',
            'planeta_origen' => $this->obtenerPlaneta($datos['homeworld']),
            'origen_swapi' => true,
            'swapi_id' => $id,
        ];
    }

    private function consultar(string $ruta, array $parametros, string $mensajeNoEncontrado): array
    {
        try {
            $respuesta = Http::timeout(10)->get(config('services.swapi.url').$ruta, $parametros);
        } catch (ConnectionException) {
            throw ValidationException::withMessages([
                'swapi_id' => 'SWAPI no responde. Intenta de nuevo en unos minutos.',
            ]);
        }

        if ($respuesta->notFound()) {
            throw ValidationException::withMessages([
                'swapi_id' => $mensajeNoEncontrado,
            ]);
        }

        if ($respuesta->failed()) {
            throw ValidationException::withMessages([
                'swapi_id' => 'SWAPI respondió con un error. Intenta de nuevo en unos minutos.',
            ]);
        }

        return $respuesta->json();
    }

    private function obtenerPlaneta(string $url): ?string
    {
        try {
            $respuesta = Http::timeout(10)->get($url);
        } catch (ConnectionException) {
            return null;
        }

        return $respuesta->successful() ? $respuesta->json('name') : null;
    }

    private function convertirNumero(string $valor, int $maximo): ?int
    {
        $numero = str_replace(',', '', $valor);

        if (! is_numeric($numero)) {
            return null;
        }

        $numero = (int) round((float) $numero);

        return $numero >= 1 && $numero <= $maximo ? $numero : null;
    }
}
