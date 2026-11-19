<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTransaccionApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'comercio_id' => [
            'required',
            Rule::exists('comercios', 'id')
                ->where(
                    fn ($query) => $query->where(
                        'user_id',
                        $this->user()->id
                    )
                ),
        ],

        'cliente_nombre' => [
            'required',
            'string',
            'min:3',
            'max:255',
        ],

        'monto' => [
            'required',
            'numeric',
            'decimal:0,2',
            'min:0.01',
            'max:99999999.99',
        ],

        'estado' => ['prohibited'],
    ];
}
    public function messages(): array
    {
        return [
            'comercio_id.required' => 'Debes indicar un comercio.',
            'comercio_id.exists' => 'Ese comercio no está disponible en tu cuenta.',

            'cliente_nombre.required' => 'Debes indicar el nombre del cliente.',
            'cliente_nombre.min' => 'El nombre del cliente es demasiado corto.',

            'monto.required' => 'Debes indicar un monto.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a cero.',

            'estado.in' => 'El estado indicado no es válido.',
            'monto.max' => 'El monto no puede superar 99,999,999.99.',
            'monto.decimal' => 'El monto admite como máximo dos decimales.',
            'estado.prohibited' => 'El estado no se envía al crear: toda transacción nace Iniciada.',
        ];
    }
}