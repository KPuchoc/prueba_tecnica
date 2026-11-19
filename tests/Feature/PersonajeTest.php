<?php

namespace Tests\Feature;

use App\Models\Personaje;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonajeTest extends TestCase
{
    use RefreshDatabase;

    public function test_crea_un_personaje_con_datos_validos(): void
    {
        $respuesta = $this->post('/personajes', [
            'nombre' => 'Mi personaje',
            'altura' => 180,
            'masa' => 80,
            'genero' => 'masculino',
        ]);

        $respuesta->assertRedirect('/personajes');
        $this->assertDatabaseHas('personajes', ['nombre' => 'Mi personaje', 'estado' => true]);
    }

    public function test_no_crea_un_personaje_con_datos_invalidos(): void
    {
        $respuesta = $this->post('/personajes', [
            'nombre' => 'Lu',
            'altura' => 350,
            'genero' => 'otro',
        ]);

        $respuesta->assertSessionHasErrors(['nombre', 'altura', 'genero']);
        $this->assertDatabaseCount('personajes', 0);
    }

    public function test_actualiza_un_personaje(): void
    {
        $personaje = Personaje::create(['nombre' => 'Mi personaje', 'genero' => 'n/a']);

        $this->put("/personajes/{$personaje->id}", ['nombre' => 'Nombre nuevo', 'genero' => 'femenino']);

        $this->assertDatabaseHas('personajes', ['id' => $personaje->id, 'nombre' => 'Nombre nuevo']);
    }

    public function test_eliminar_cambia_el_estado_a_cero(): void
    {
        $personaje = Personaje::create(['nombre' => 'Mi personaje', 'genero' => 'n/a']);

        $this->delete("/personajes/{$personaje->id}");

        $this->assertDatabaseHas('personajes', ['id' => $personaje->id, 'estado' => false]);
    }
}
