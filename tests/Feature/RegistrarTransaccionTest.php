<?php

namespace Tests\Feature;

use App\Models\{Comercio, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegistrarTransaccionTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_transaccion_con_comision_de_tarjeta(): void
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
            'metodo_pago' => 'tarjeta',
        ])
            ->assertCreated()
            ->assertJsonPath('data.estado', 'Iniciada');

        $this->assertDatabaseHas('transacciones', [
            'comercio_id' => $comercio->id,
            'metodo_pago' => 'tarjeta',
            'comision' => 3.50,
        ]);
    }

    public function test_comision_fija_en_transferencia(): void
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
            'metodo_pago' => 'transferencia',
        ])->assertCreated();

        $this->assertDatabaseHas('transacciones', [
            'comercio_id' => $comercio->id,
            'metodo_pago' => 'transferencia',
            'comision' => 1.50,
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
            'metodo_pago' => 'tarjeta',
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
            'cliente_nombre' => 'Cliente Prueba',
            'monto' => 100,
            'metodo_pago' => 'bitcoin',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('metodo_pago');
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
            'metodo_pago' => 'tarjeta',
        ])->assertUnauthorized();
    }
    public function test_comision_de_billetera_es_dos_por_ciento(): void
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
        'metodo_pago' => 'billetera',
    ])->assertCreated();

    $this->assertDatabaseHas('transacciones', [
        'comercio_id' => $comercio->id,
        'metodo_pago' => 'billetera',
        'comision' => 2.00,
    ]);
}
}