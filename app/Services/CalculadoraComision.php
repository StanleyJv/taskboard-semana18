<?php

namespace App\Services;

use InvalidArgumentException;

class CalculadoraComision
{
    /** @param \App\Services\Comisiones\ReglaComision[] $reglas */
    public function __construct(private array $reglas)
    {
    }

    public function calcular(string $metodo, float $monto): float
    {
        foreach ($this->reglas as $regla) {
            if ($regla->aplica($metodo)) {
                return $regla->calcular($monto);
            }
        }

        throw new InvalidArgumentException(
            "Método de pago no soportado: {$metodo}"
        );
    }
}