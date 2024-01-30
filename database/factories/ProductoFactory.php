<?php

namespace Database\Factories;

use App\Models\Almacenarea;
use App\Models\Category;
use App\Models\Estante;
use App\Models\Marca;
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
        $tipocambio = 3.75;
        $priceusbuy = $this->faker->randomFloat(2, 0.10, 1000);
        $ganancia = number_format($priceusbuy * 0.10, 2, '.', '');
        $categories = Category::all();
        $almacenareas =  Almacenarea::all();
        $estantes = Estante::all();
        $marcas = Marca::all();

        if (count($categories) > 0) {
            $category = $categories->random();
            $subcategories = $category->subcategories;
            if (count($subcategories) > 0) {
                $subcategory = $subcategories->random()->id;
            }
        }

        return [
            'name' => $this->faker->text(90),
            'modelo' => $this->faker->word(),
            'pricebuy' => number_format($priceusbuy * $tipocambio, 2, '.', ''),
            'priceusbuy' => $priceusbuy,
            'pricesale' => number_format(($priceusbuy + $ganancia) * $tipocambio, 2, '.', ''),
            'igv' => 0,
            'publicado' => $this->faker->randomElement([0, 1]),
            'sku' => $this->faker->bothify('##??####??##??##'),
            'views' => 0,
            'minstock' => 5,
            'codefabricante' => $this->faker->bothify('????-########'),
            'almacenarea_id' => count($almacenareas) > 0 ? $almacenareas->random()->id : null,
            'estante_id' => count($estantes) > 0 ? $estantes->random()->id : null,
            'marca_id' => count($marcas) > 0 ? $marcas->random()->id : null,
            'category_id' => $category->id ?? null,
            'subcategory_id' => $subcategory ?? null,
            'unit_id' => 1,
        ];
    }
}
