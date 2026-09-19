<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreditoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha_otorgamiento' => ['required', 'date'],
            'monto' => ['required', 'numeric', 'min:200'],
            'tasa_interes' => ['required', 'numeric', 'min:0'],
            'plazo' => ['required', 'integer', 'min:1', 'max:600'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'fecha_otorgamiento.required' => 'La fecha de otorgamiento es obligatoria.',
            'monto.required' => 'El monto del crédito es obligatorio.',
            'monto.min' => 'El monto mínimo del crédito es de $200.00.',
            'tasa_interes.required' => 'La tasa de interés (o recargo) es obligatoria.',
            'tasa_interes.min' => 'La tasa de interés no puede ser negativa.',
            'plazo.required' => 'El plazo es obligatorio.',
            'plazo.min' => 'El plazo debe ser de al menos 1 mes.',
        ];
    }
}
