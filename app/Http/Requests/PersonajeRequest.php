<?php

namespace App\Http\Requests;

use App\Models\Personaje;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'altura' => ['nullable', 'integer', 'min:1', 'max:'.Personaje::ALTURA_MAXIMA],
            'masa' => ['nullable', 'integer', 'min:1', 'max:'.Personaje::MASA_MAXIMA],
            'color_cabello' => ['nullable', 'string', 'max:100'],
            'color_ojos' => ['nullable', 'string', 'max:100'],
            'genero' => ['required', Rule::in(Personaje::GENEROS)],
            'planeta_origen' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no debe superar los 100 caracteres.',
            'altura.integer' => 'La altura debe ser un número entero.',
            'altura.min' => 'La altura debe ser un número positivo.',
            'altura.max' => 'La altura no debe ser mayor a :max.',
            'masa.integer' => 'La masa debe ser un número entero.',
            'masa.min' => 'La masa debe ser un número positivo.',
            'masa.max' => 'La masa no debe ser mayor a :max.',
            'color_cabello.max' => 'El color de cabello no debe superar los 100 caracteres.',
            'color_ojos.max' => 'El color de ojos no debe superar los 100 caracteres.',
            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'El género debe ser masculino, femenino o n/a.',
            'planeta_origen.max' => 'El planeta de origen no debe superar los 100 caracteres.',
        ];
    }
}
