<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;

        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento_identidad' => [
                'required', 'string', 'max:30',
                Rule::unique('clientes', 'documento_identidad')->ignore($clienteId),
            ],
            'telefono' => ['nullable', 'string', 'max:25'],
            'correo' => [
                'nullable', 'email', 'max:150',
                Rule::unique('clientes', 'correo')->ignore($clienteId),
            ],
            'direccion' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'El nombre del cliente es obligatorio.',
            'apellidos.required' => 'El apellido del cliente es obligatorio.',
            'documento_identidad.required' => 'El documento de identidad es obligatorio.',
            'documento_identidad.unique' => 'Ya existe un cliente con ese documento de identidad.',
            'correo.unique' => 'Ya existe un cliente con ese correo.',
        ];
    }
}