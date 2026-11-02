<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearSolicitudRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo para el prototipo local: autorización real queda pendiente.
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_referencia' => ['required', 'string', 'max:120'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha_requerida' => ['required', 'date_format:Y-m-d'],
            'estado' => ['required', Rule::in(['pendiente', 'en_revision', 'finalizada'])],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_referencia.required' => 'Debes indicar una referencia de cliente.',
            'cantidad.required' => 'Debes indicar una cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser mayor que cero.',
            'fecha_requerida.required' => 'Debes indicar una fecha requerida.',
            'fecha_requerida.date_format' => 'La fecha requerida debe tener el formato AAAA-MM-DD.',
            'estado.required' => 'Debes indicar un estado.',
            'estado.in' => 'El estado debe ser pendiente, en_revision o finalizada.',
        ];
    }
}
