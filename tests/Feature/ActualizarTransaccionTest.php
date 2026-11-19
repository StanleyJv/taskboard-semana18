<?php

namespace Tests\Feature;

use App\Models\{Comercio, Transaccion, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ActualizarTransaccionTest extends TestCase
{
    use RefreshDatabase;

    public function test_actualiza_estado_correctamente(): void
    {
        $user = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $user->id,
        ]);

        $transaccion = Transaccion::create([
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
            'moneda' => 'USD',
            'metodo_pago' => 'No especificado',
            'estado' => 'Iniciada',
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/transacciones/{$transaccion->id}", [
            'estado' => 'Completada',
        ])->assertOk();

        $this->assertDatabaseHas('transacciones', [
            'id' => $transaccion->id,
            'estado' => 'Completada',
        ]);
    }

    public function test_transaccion_no_iniciada_devuelve_422(): void
    {
        $user = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $user->id,
        ]);

        $transaccion = Transaccion::create([
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
            'moneda' => 'USD',
            'metodo_pago' => 'No especificado',
            'estado' => 'Completada',
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/transacciones/{$transaccion->id}", [
            'estado' => 'Fallida',
        ])->assertStatus(422);
    }

    public function test_transaccion_ajena_devuelve_404(): void
    {
        $user = User::factory()->create();
        $otroUsuario = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $otroUsuario->id,
        ]);

        $transaccion = Transaccion::create([
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
            'moneda' => 'USD',
            'metodo_pago' => 'No especificado',
            'estado' => 'Iniciada',
        ]);

        Sanctum::actingAs($user);

        $this->putJson("/api/transacciones/{$transaccion->id}", [
            'estado' => 'Completada',
        ])->assertNotFound();
    }
}