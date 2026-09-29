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
        $cliente = $this->route('cliente');
        $clienteId = $cliente?->id;

        // Al crear no existe $cliente. Al editar, $cliente->usuario_id
        // indica si el cliente ya tiene un usuario de acceso vinculado.
        $esCreacion = $cliente === null;
        $usuarioId = $cliente?->usuario_id;
        $tieneUsuario = $usuarioId !== null;

        $reglasUsername = [
            'string',
            'max:50',
            Rule::unique('users', 'username')->ignore($usuarioId),
        ];

        $reglasPassword = [
            'string',
            'min:8',
        ];

        if ($esCreacion || $tieneUsuario) {
            // Cliente nuevo, o cliente que ya tiene usuario: el usuario es obligatorio.
            array_unshift($reglasUsername, 'required');
        } else {
            // Cliente antiguo sin acceso: es opcional, pero si se llena uno hay que llenar ambos.
            array_unshift($reglasUsername, 'nullable', 'required_with:password');
        }

        if ($esCreacion) {
            // Al crear, la contraseña es obligatoria.
            array_unshift($reglasPassword, 'required');
        } elseif ($tieneUsuario) {
            // Al editar, vacía = conservar la contraseña actual.
            array_unshift($reglasPassword, 'nullable');
        } else {
            array_unshift($reglasPassword, 'nullable', 'required_with:username');
        }

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
            'username' => $reglasUsername,
            'password' => $reglasPassword,
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

            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.required_with' => 'Si defines una contraseña, también debes indicar el nombre de usuario.',
            'username.string' => 'El nombre de usuario debe ser texto.',
            'username.max' => 'El nombre de usuario no puede superar los 50 caracteres.',
            'username.unique' => 'Ese nombre de usuario ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.required_with' => 'Si defines un usuario, también debes indicar la contraseña.',
            'password.string' => 'La contraseña debe ser válida.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}