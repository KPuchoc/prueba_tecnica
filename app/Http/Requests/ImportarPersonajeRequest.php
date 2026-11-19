<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportarPersonajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'swapi_id' => ['required', 'integer', 'between:1,83'],
        ];
    }

    public function messages(): array
    {
        return [
            'swapi_id.required' => 'El ID de SWAPI es obligatorio.',
            'swapi_id.integer' => 'El ID de SWAPI debe ser un número entero.',
            'swapi_id.between' => 'El ID de SWAPI debe estar entre 1 y 83.',
        ];
    }
}
