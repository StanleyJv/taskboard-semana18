<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GuardarTransaccionApiRequest;
use App\Http\Resources\TransaccionResource;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransaccionApiController extends Controller
{
    public function index(Request $request)
    {
        $datos = $request->validate([
            'estado' => [
                'nullable',
                Rule::in(['Iniciada', 'Completada', 'Fallida']),
            ],
        ]);

        $estado = $datos['estado'] ?? null;

        $transacciones = Transaccion::whereHas(
            'comercio',
            fn ($q) => $q->where(
                'user_id',
                $request->user()->id
            )
        )
            ->when(
                $estado,
                fn ($q) => $q->where('estado', $estado)
            )
            ->latest()
            ->get();

        return TransaccionResource::collection($transacciones);
    }

    public function store(GuardarTransaccionApiRequest $request)
    {
        $datos = $request->validated();

        $datos['moneda'] = 'USD';
        $datos['metodo_pago'] = 'No especificado';
        $datos['estado'] = 'Iniciada';

        $t = DB::transaction(function () use ($datos) {

            $t = Transaccion::create($datos);

            $t->eventos()->create([
                'tipo_evento' => 'creacion',
                'detalle' => 'Transacción iniciada',
                'estado_anterior' => 'Iniciada',
                'estado_nuevo' => 'Iniciada',
            ]);

            return $t;
        });

        return (new TransaccionResource($t))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Transaccion $transaccion)
    {
        $this->autorizar($transaccion);

        return new TransaccionResource($transaccion);
    }

    public function filtrar(Request $request)
    {
        $datos = $request->validate([
            'estado' => [
                'nullable',
                Rule::in(['Iniciada', 'Completada', 'Fallida']),
            ],
            'desde' => [
                'nullable',
                'date',
            ],
            'hasta' => [
                'nullable',
                'date',
                'after_or_equal:desde',
            ],
            'monto_min' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'monto_max' => [
                'nullable',
                'numeric',
                'min:0',
                Rule::when(
                    $request->filled('monto_min'),
                    ['gte:monto_min']
                ),
            ],
        ]);

        $query = Transaccion::whereHas(
            'comercio',
            fn ($q) => $q->where(
                'user_id',
                $request->user()->id
            )
        );

        $query
            ->when(
                $datos['estado'] ?? null,
                fn ($q, $estado) => $q->where('estado', $estado)
            )
            ->when(
                $datos['desde'] ?? null,
                fn ($q, $desde) => $q->whereDate(
                    'created_at',
                    '>=',
                    $desde
                )
            )
            ->when(
                $datos['hasta'] ?? null,
                fn ($q, $hasta) => $q->whereDate(
                    'created_at',
                    '<=',
                    $hasta
                )
            )
            ->when(
                $datos['monto_min'] ?? null,
                fn ($q, $monto) => $q->where(
                    'monto',
                    '>=',
                    $monto
                )
            )
            ->when(
                $datos['monto_max'] ?? null,
                fn ($q, $monto) => $q->where(
                    'monto',
                    '<=',
                    $monto
                )
            );

        return TransaccionResource::collection(
            $query->latest()->get()
        );
    }

    public function destroy(Transaccion $transaccion)
    {
        $this->autorizar($transaccion);

        $transaccion->delete();

        return response()->noContent();
    }

    private function autorizar(Transaccion $transaccion): void
    {
        abort_unless(
            $transaccion->comercio->user_id === auth()->id(),
            404
        );
    }
}