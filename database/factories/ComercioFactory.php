<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComercioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre_comercio' => fake()->company(),
            'rubro' => fake()->randomElement([
                'Tecnología',
                'Comercio',
                'Servicios',
                'Alimentos',
            ]),
            'fecha_afiliacion' => fake()->date(),
            'user_id' => User::factory(),
        ];
    }
}