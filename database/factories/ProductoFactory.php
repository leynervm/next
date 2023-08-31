<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {


        return [
            'name' => $this->faker->name(),
            'modelo' => $this->faker->word(),
            'pricebuy' => $this->faker->numerify('####.##'),
            'priceusbuy' => 0,
            'pricesale' => 0,
            'percent' => 18,
            'igv' => 0,
            'publicado' => $this->faker->randomElement([0, 1]),
            'sku' => $this->faker->randomKey([$this->faker->randomLetter(), $this->faker->randomNumber()]),
            'views' => 0,
            'almacenarea_id' => $this->faker->randomElement([1, 2]),
            'estante_id' => $this->faker->randomElement([1, 2]),
            'marca_id' =>  $this->faker->randomElement([1, 2, 3]),
            'category_id' =>  $this->faker->randomElement([1, 2, 3]),
            'subcategory_id' =>  $this->faker->randomElement([1, 2, 3]),
            'tribute_id' => 1,
            'unit_id' => 1,
            'user_id' => 1,
        ];
    }
}
