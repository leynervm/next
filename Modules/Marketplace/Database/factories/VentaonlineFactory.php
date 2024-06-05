<?php

namespace Modules\Marketplace\Database\factories;

use App\Models\Client;
use App\Models\Moneda;
use App\Models\Seriecomprobante;
use App\Models\Typepayment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Marketplace\Entities\Shipmenttype;
use Modules\Marketplace\Entities\Ventaonline;

class VentaonlineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ventaonline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $seriecomprobante = Seriecomprobante::whereHas('typecomprobante', function ($query) {
            $query->whereIn('code', ['VT', '01', '03']);
        })->get()->random();

        $seriecompleta = $seriecomprobante->serie . $this->faker->numerify('-####');
        $total = $this->faker->randomFloat(2, 5, 2500);

        return [
            'date' => $this->faker->dateTime($max = 'now', $timezone = 'America/Lima'),
            'seriecompleta' => $seriecompleta,
            'exonerado' => $total,
            'gravado' => 0,
            'inafecto' => 0,
            'gratuito' => 0,
            'descuento' => 0,
            'otros' => 0,
            'igv' => 0,
            'igvgratuito' => 0,
            'subtotal' => $total,
            'total' => $total,
            'tipocambio' => null,
            'paymentactual' => $total,
            'moneda_id' => Moneda::default()->first()->id,
            'typepayment_id' => Typepayment::default()->first()->id,
            'seriecomprobante_id' => $seriecomprobante->id,
            'client_id' => Client::all()->random()->id,
            'user_id' => 1,
            'shipmenttype_id' => Shipmenttype::all()->random()->id,
        ];
    }
}
