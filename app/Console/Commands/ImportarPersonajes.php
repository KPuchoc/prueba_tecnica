<?php

namespace App\Console\Commands;

use App\Services\PersonajeService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

#[Signature('personajes:importar {ids* : IDs de SWAPI entre 1 y 83}')]
#[Description('Importa varios personajes desde SWAPI')]
class ImportarPersonajes extends Command
{
    public function handle(PersonajeService $personajeService): int
    {
        foreach ($this->argument('ids') as $id) {
            if (! ctype_digit($id) || $id < 1 || $id > 83) {
                $this->error("{$id}: el ID debe estar entre 1 y 83.");

                continue;
            }

            try {
                $personaje = $personajeService->importarDesdeSwapi((int) $id);
                $this->info("{$id}: {$personaje->nombre} importado.");
            } catch (ValidationException $error) {
                $this->error("{$id}: {$error->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
