<?php

namespace Modules\Marketplace\Database\factories;

use App\Models\Client;
use App\Models\Direccion;
use App\Models\Moneda;
use App\Models\Seriecomprobante;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Marketplace\Entities\Shipmenttype;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Marketplace\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $total = $this->faker->randomFloat(2, 5, 2500);
        $shipmenttype = Shipmenttype::all()->random();

        return [
            'date' => $this->faker->dateTime($max = 'now', $timezone = 'America/Lima'),
            'seriecompleta' => $this->faker->numerify('ORDER-###'),
            'exonerado' => $total,
            'gravado' => 0,
            'igv' => 0,
            'subtotal' => $total,
            'total' => $total,
            'tipocambio' => null,
            'receiverinfo' => [
                'document' => $this->faker->numerify('########'),
                'name' => $this->faker->name,
                'telefono' => $this->faker->numerify('#########'),
            ],
            'direccion_id' => $shipmenttype->isEnviodomicilio() ? Direccion::all()->random()->id : null,
            'moneda_id' => Moneda::default()->first()->id,
            'client_id' => Client::all()->random()->id,
            'user_id' => 1,
            'shipmenttype_id' => $shipmenttype->id,
        ];
    }
}
