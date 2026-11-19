<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    public const GENEROS = ['masculino', 'femenino', 'n/a'];

    public const ALTURA_MAXIMA = 300;

    public const MASA_MAXIMA = 500;

    protected $fillable = [
        'nombre',
        'altura',
        'masa',
        'color_cabello',
        'color_ojos',
        'genero',
        'planeta_origen',
        'origen_swapi',
        'swapi_id',
    ];

    protected $casts = [
        'origen_swapi' => 'boolean',
        'estado' => 'boolean',
    ];
}
