<?php

namespace Modules\Marketplace\Database\factories;

use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class TvitemMarketplaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Marketplace\Entities\TvitemMarketplace::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $pricebuy = $this->faker->randomFloat(2, 1, 1500);
        $cantidad = $this->faker->numberBetween(1, 5);
        $price =  $pricebuy + ($pricebuy * 0.30);

        return [
            'date' => now('America/Lima'),
            'cantidad' => $cantidad,
            'pricebuy' => $pricebuy,
            'price' => $price,
            'igv' => 0,
            'subtotaligv' => 0,
            'subtotal' => $price * $cantidad,
            'total' => $price * $cantidad,
            'status' => 0,
            'alterstock' => Almacen::DISMINUIR_STOCK,
            'gratuito' => 0,
            'increment' => 0,
            'almacen_id' => null,
            'producto_id' => Producto::all()->random()->id,
            'user_id' => 1
        ];
    }
}
