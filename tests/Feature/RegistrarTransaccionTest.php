<?php

namespace Tests\Feature;

use App\Models\{Comercio, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegistrarTransaccionTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_transaccion_correctamente(): void
    {
        $user = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/transacciones', [
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
        ])
            ->assertCreated()
            ->assertJsonPath('data.estado', 'Iniciada');

        $this->assertDatabaseHas('transacciones', [
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
            'estado' => 'Iniciada',
        ]);
    }

    public function test_transaccion_guarda_valores_predeterminados(): void
    {
        $user = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/transacciones', [
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
        ])->assertCreated();

        $this->assertDatabaseHas('transacciones', [
            'comercio_id' => $comercio->id,
            'moneda' => 'USD',
            'metodo_pago' => 'No especificado',
            'estado' => 'Iniciada',
        ]);
    }

    public function test_comercio_ajeno_devuelve_422(): void
    {
        $user = User::factory()->create();
        $otroUsuario = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $otroUsuario->id,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/transacciones', [
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('comercio_id');
    }

    public function test_datos_invalidos_devuelven_422(): void
    {
        $user = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/transacciones', [
            'comercio_id' => $comercio->id,
            'cliente_nombre' => '',
            'monto' => 100,
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('cliente_nombre');
    }

    public function test_sin_token_devuelve_401(): void
    {
        $user = User::factory()->create();

        $comercio = Comercio::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->postJson('/api/transacciones', [
            'comercio_id' => $comercio->id,
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
        ])->assertUnauthorized();
    }
}