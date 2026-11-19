<?php

namespace App\Services;

use App\Enums\EstadoTransaccion;
use App\Models\{Transaccion, User};
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Validation\ValidationException;

class TransaccionService
{
    public function __construct(
        private CalculadoraComision $calculadora
    ) {
    }

    public function registrar(User $usuario, array $datos): Transaccion
    {
        $comercio = $usuario->comercios()
            ->findOrFail($datos['comercio_id']);

        $comision = $this->calculadora->calcular(
            $datos['metodo_pago'],
            (float) $datos['monto']
        );

        $t = DB::transaction(function () use (
            $comercio,
            $datos,
            $comision
        ) {
            $t = $comercio->transacciones()->create([
                'cliente_nombre' => $datos['cliente_nombre'],
                'monto' => $datos['monto'],
                'comision' => $comision,
                'moneda' => 'USD',
                'metodo_pago' => $datos['metodo_pago'],
                'estado' => EstadoTransaccion::Iniciada,
            ]);

            $t->eventos()->create([
                'tipo_evento' => 'creacion',
                'detalle' => 'Transacción iniciada',
                'estado_anterior' => EstadoTransaccion::Iniciada->value,
                'estado_nuevo' => EstadoTransaccion::Iniciada->value,
            ]);

            return $t;
        });

        Log::info("Transacción {$t->id} registrada");

        return $t;
    }

    public function cambiarEstado(
        User $usuario,
        Transaccion $transaccion,
        string $nuevo
    ): Transaccion {
        $usuario->comercios()
            ->findOrFail($transaccion->comercio_id);

        if ($transaccion->estado !== EstadoTransaccion::Iniciada) {
            throw ValidationException::withMessages([
                'estado' => 'Solo se puede cambiar una transacción Iniciada.',
            ]);
        }

        $t = DB::transaction(function () use (
            $transaccion,
            $nuevo
        ) {
            $estadoAnterior = $transaccion->estado->value;

            $transaccion->update([
                'estado' => $nuevo,
            ]);

            $transaccion->eventos()->create([
                'tipo_evento' => 'cambio_estado',
                'detalle' => "Estado cambiado de {$estadoAnterior} a {$nuevo}",
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $nuevo,
            ]);

            return $transaccion->refresh();
        });

        Log::info(
            "Transacción {$t->id} cambió de estado a {$nuevo}"
        );

        return $t;
    }
}