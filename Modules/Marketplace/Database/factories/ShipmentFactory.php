<?php

namespace Modules\Marketplace\Database\factories;

use App\Models\Ubigeo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Marketplace\Entities\Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'direccion' => $this->faker->address(),
            'referencia' => $this->faker->paragraph(1),
            'ubigeo_id' => Ubigeo::all()->random()->id
        ];
    }
}
