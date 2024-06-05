<?php

namespace Database\Factories;

use App\Models\Ubigeo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direccion>
 */
class DireccionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->streetName(),
            'referencia' => $this->faker->streetAddress(),
            'ubigeo_id' => Ubigeo::all()->random()->id
        ];
    }
}
