<?php

namespace Database\Factories;

use App\Models\Almacenarea;
use App\Models\Category;
use App\Models\Estante;
use App\Models\Marca;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producto>
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
        $pricebuy = $this->faker->randomFloat(2, 0.10, 1000);
        $pricesale = $pricebuy + ($pricebuy * 30 / 100);
        $category = Category::all()->random();
        $subcategory = $category->subcategories()->inRandomOrder()->first();
        $marca = Marca::all()->random();

        // $almacenareas =  Almacenarea::all();
        // $estantes = Estante::all();

        return [
            'name' => $this->faker->text(120),
            'modelo' => $this->faker->word(),
            'pricebuy' => number_format($pricebuy, 2, '.', ''),
            'pricesale' => number_format($pricesale, 2, '.', ''),
            'igv' => 0,
            'publicado' => $this->faker->randomElement([0, 1]),
            'code' => $this->faker->bothify('##??####??##??##'),
            'codefabricante' => $this->faker->bothify('????-########'),
            'views' => 0,
            'minstock' => rand(1, 5),
            'almacenarea_id' => null,
            'estante_id' => null,
            'marca_id' => $marca->id,
            'category_id' => $category->id ?? null,
            'subcategory_id' => $subcategory->id ?? null,
            'unit_id' => 1,
            'user_id' => User::all()->random()->id
        ];
    }
}
