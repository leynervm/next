<?php

namespace Modules\Almacen\Database\factories;

use App\Models\Category;
use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Almacen\Entities\Almacenarea;
use Modules\Almacen\Entities\Estante;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Almacen\Entities\Producto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $tipocambio = 3.75;
        $priceusbuy = $this->faker->randomFloat(2, 0.10, 1000);
        $ganancia = number_format($priceusbuy * 0.10, 2, '.', '');
        $category = Category::all()->random();

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
            'codefabricante' => $this->faker->bothify('????-########'),
            'almacenarea_id' => Almacenarea::all()->random()->id,
            'estante_id' => Estante::all()->random()->id,
            'marca_id' =>  Marca::all()->random()->id,
            'category_id' =>  $category->id,
            'subcategory_id' =>  $category->subcategories->random()->id,
            'unit_id' => 1,
        ];
    }
}
