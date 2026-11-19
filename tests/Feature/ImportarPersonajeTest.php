<?php

namespace Tests\Feature;

use App\Models\Personaje;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportarPersonajeTest extends TestCase
{
    use RefreshDatabase;

    public function test_importa_y_traduce_los_campos_de_swapi(): void
    {
        Http::fake([
            '*/people/16/' => Http::response([
                'name' => 'Jabba Desilijic Tiure',
                'height' => '175',
                'mass' => '1,358',
                'hair_color' => 'n/a',
                'eye_color' => 'orange',
                'gender' => 'hermaphrodite',
                'homeworld' => 'https://swapi.py4e.com/api/planets/24/',
            ]),
            '*/planets/24/' => Http::response(['name' => 'Nal Hutta']),
        ]);

        $this->post('/personajes/importar', ['swapi_id' => 16]);

        $this->assertDatabaseHas('personajes', [
            'nombre' => 'Jabba Desilijic Tiure',
            'altura' => 175,
            'masa' => null,
            'genero' => 'n/a',
            'planeta_origen' => 'Nal Hutta',
            'origen_swapi' => true,
            'swapi_id' => 16,
        ]);
    }

    public function test_no_importa_dos_veces_el_mismo_personaje(): void
    {
        Personaje::create(['nombre' => 'Luke Skywalker', 'genero' => 'masculino', 'origen_swapi' => true, 'swapi_id' => 1]);

        $respuesta = $this->post('/personajes/importar', ['swapi_id' => 1]);

        $respuesta->assertSessionHasErrors(['swapi_id' => 'Luke Skywalker ya fue importado.']);
        $this->assertDatabaseCount('personajes', 1);
    }

    public function test_muestra_error_si_el_id_no_existe_en_swapi(): void
    {
        Http::fake(['*' => Http::response(['detail' => 'Not found'], 404)]);

        $respuesta = $this->post('/personajes/importar', ['swapi_id' => 17]);

        $respuesta->assertSessionHasErrors(['swapi_id' => 'No existe un personaje con el ID 17 en SWAPI.']);
    }

    public function test_muestra_error_si_swapi_no_responde(): void
    {
        Http::fake(['*' => Http::failedConnection()]);

        $respuesta = $this->post('/personajes/importar', ['swapi_id' => 1]);

        $respuesta->assertSessionHasErrors(['swapi_id' => 'SWAPI no responde. Intenta de nuevo en unos minutos.']);
    }

    public function test_valida_que_el_id_este_entre_1_y_83(): void
    {
        $respuesta = $this->post('/personajes/importar', ['swapi_id' => 84]);

        $respuesta->assertSessionHasErrors(['swapi_id' => 'El ID de SWAPI debe estar entre 1 y 83.']);
    }
}
