<?php

namespace App\Services;

use App\Models\{Transaccion, User};
use Illuminate\Support\Facades\{DB, Log};

class TransaccionService
{
    public function registrar(User $usuario, array $datos): Transaccion
    {
        $comercio = $usuario->comercios()
            ->findOrFail($datos['comercio_id']);

        $datos['moneda'] = 'USD';
        $datos['metodo_pago'] = 'No especificado';
        $datos['estado'] = 'Iniciada';

        $t = DB::transaction(function () use ($comercio, $datos) {

            $t = $comercio->transacciones()->create([
                'cliente_nombre' => $datos['cliente_nombre'],
                'monto' => $datos['monto'],
                'moneda' => $datos['moneda'],
                'metodo_pago' => $datos['metodo_pago'],
                'estado' => $datos['estado'],
            ]);

            $t->eventos()->create([
                'tipo_evento' => 'creacion',
                'detalle' => 'Transacción iniciada',
                'estado_anterior' => 'Iniciada',
                'estado_nuevo' => 'Iniciada',
            ]);

            return $t;
        });

        Log::info("Transacción {$t->id} registrada");

        return $t;
    }
}