<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $empleadoId = $this->route('empleado')?->id;

    $reglas = [
        'username' => [
            'required',
            'string',
            'max:50',
            Rule::unique('users', 'username')->ignore($empleadoId),
        ],
    ];

    /*
     * Al crear el empleado la contraseña es obligatoria.
     * Al editarlo es opcional para conservar la contraseña existente.
     */
    if ($this->isMethod('POST')) {
        $reglas['password'] = [
            'required',
            'string',
            'min:8',
        ];
    } else {
        $reglas['password'] = [
            'nullable',
            'string',
            'min:8',
        ];

        $reglas['estado'] = [
            'required',
            Rule::in(['Activo', 'Inactivo']),
        ];
    }

    return $reglas;
}

    public function messages(): array
    {
        return [
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.string' => 'El nombre de usuario debe ser texto.',
            'username.max' => 'El nombre de usuario no puede superar los 50 caracteres.',
            'username.unique' => 'Ese nombre de usuario ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser válida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',

            'estado.required' => 'El estado del empleado es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',
        ];
    }
}