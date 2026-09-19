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

    protected function prepareForValidation(): void
    {
        $dui = $this->input('documento_identidad');

        // Permitimos que el usuario escriba el DUI con o sin guion.
        // Si contiene otros caracteres, dejamos el valor original para
        // que la validación lo rechace en lugar de eliminar información.
        if (is_string($dui) && preg_match('/^[\d\s-]+$/', $dui)) {
            $digitos = preg_replace('/\D/', '', $dui);

            if (strlen($digitos) === 9) {
                $dui = substr($digitos, 0, 8) . '-' . substr($digitos, 8, 1);
            }
        }

        $this->merge([
            'documento_identidad' => $dui,
        ]);
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente')?->id;

        return [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento_identidad' => [
                'required',
                'string',
                'regex:/^\d{8}-\d$/',
                'max:10',
                Rule::unique('clientes', 'documento_identidad')->ignore($clienteId),
            ],
            'telefono' => [
    'nullable',
    'regex:/^\d{8}$/',
],
            'correo' => [
                'nullable',
                'email',
                'max:150',
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
            'documento_identidad.required' => 'El DUI es obligatorio.',
            'documento_identidad.regex' => 'El DUI debe tener el formato 12345678-9.',
            'documento_identidad.unique' => 'Ya existe un cliente con ese DUI.',
            'telefono.regex' => 'El teléfono debe tener exactamente 8 dígitos.',
            'correo.unique' => 'Ya existe un cliente con ese correo.',
        ];
    }
}