<?php

namespace Modules\Marketplace\Database\Seeders;

use App\Models\Direccion;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Marketplace\Entities\Order;
use Modules\Marketplace\Entities\Trackingstate;
use Modules\Marketplace\Entities\TvitemMarketplace;
use Nwidart\Modules\Facades\Module;
use Faker\Factory as FakerFactory;
use Luecano\NumeroALetras\NumeroALetras;

class SeedOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $faker = FakerFactory::create();

        $tvitems = TvitemMarketplace::factory()->count(3)->for(
            Order::factory(),
            'tvitemable'
        )->create();

        // SERIECOMPROBANTES
        Sucursal::first()->seriecomprobantes()->createMany([
            [
                'serie' => 'F001',
                'contador' => 0,
                'default' => 0,
                'typecomprobante_id' => 2,
            ],
            [
                'serie' => 'B001',
                'contador' => 0,
                'default' => 0,
                'typecomprobante_id' => 3,
            ]
        ]);

        $orders = Order::factory(30)
            ->hasTvitems(3)->create();

        foreach ($orders as $item) {
            $item->trackings()->createMany([
                [
                    'date' => now(),
                    'descripcion' => $faker->paragraph(2),
                    'trackingstate_id' => Trackingstate::default()->first()->id,
                    'user_id' => 1
                ],
                [
                    'date' => now(),
                    'descripcion' => $faker->paragraph(2),
                    'trackingstate_id' => 2,
                    'user_id' => 1
                ],
                [
                    'date' => now(),
                    'descripcion' => $faker->paragraph(1),
                    'trackingstate_id' => 3,
                    'user_id' => 1
                ],
                [
                    'date' => now(),
                    'descripcion' => $faker->paragraph(1),
                    'trackingstate_id' => 4,
                    'user_id' => 1
                ]
            ]);

            if ($item->shipmenttype->isRecojotienda()) {
                $item->entrega()->create([
                    'date' => now(),
                    'sucursal_id' => Sucursal::all()->random()->id,
                ]);
            }

            if (Module::isEnabled('Facturacion')) {

                $percent = mi_empresa()->igv;
                $seriecomprobante = Seriecomprobante::whereHas('typecomprobante', function ($query) {
                    $query->whereIn('code', ['VT', '01', '03']);
                })->get()->random();

                $seriecompleta = $seriecomprobante->serie . $faker->numerify('-####');
                $sucursal_id = $item->shipmenttype->isRecojotienda() ?  $item->entrega->sucursal_id : Sucursal::default()->first()->id;

                $leyenda = new NumeroALetras();
                $comprobante = $item->comprobante()->create([
                    'seriecompleta' => $seriecompleta,
                    'date' =>  $item->date,
                    'expire' => $item->date,
                    'direccion' => null,
                    'exonerado' => number_format($item->exonerado, 3, '.', ''),
                    'gravado' => number_format($item->gravado, 3, '.', ''),
                    'gratuito' => 0,
                    'inafecto' => 0,
                    'descuento' => 0,
                    'otros' => 0,
                    'igv' => number_format($item->igv, 3, '.', ''),
                    'igvgratuito' => 0,
                    'subtotal' => number_format($item->subtotal, 3, '.', ''),
                    'total' => number_format($item->total, 3, '.', ''),
                    'paymentactual' => number_format($item->total, 4, '.', ''),
                    'percent' => $percent,
                    'referencia' => $item->seriecompleta,
                    'leyenda' => $leyenda->toInvoice($item->total, 2, 'NUEVOS SOLES'),
                    'client_id' => $item->client_id,
                    'typepayment_id' => Typepayment::default()->first()->id,
                    'seriecomprobante_id' => $seriecomprobante->id,
                    'moneda_id' => $item->moneda_id,
                    'sucursal_id' => $sucursal_id,
                    'user_id' => $item->user_id
                ]);

                $counter = 1;
                foreach ($item->tvitems as $tvitem) {
                    if ($tvitem->gratuito) {
                        $afectacion = $tvitem->igv > 0 ? '15' : '21';
                    } else {
                        $afectacion = $tvitem->igv > 0 ? '10' : '20';
                    }

                    $codeafectacion = $tvitem->igv > 0 ? '1000' : '9997';
                    $nameafectacion = $tvitem->igv > 0 ? 'IGV' : 'EXO';
                    $typeafectacion = $tvitem->igv > 0 ? 'VAT' : 'VAT';
                    $abreviatureafectacion = $tvitem->igv > 0 ? 'S' : 'E';

                    $comprobante->facturableitems()->create([
                        'item' => $counter,
                        'descripcion' => $tvitem->producto->name,
                        'code' => $tvitem->producto->code,
                        'cantidad' => $tvitem->cantidad,
                        'price' => number_format($tvitem->price, 2, '.', ''),
                        'igv' => number_format($tvitem->igv, 2, '.', ''),
                        'subtotaligv' => number_format($tvitem->subtotaligv, 2, '.', ''),
                        'subtotal' => number_format($tvitem->subtotal, 2, '.', ''),
                        'total' => number_format($tvitem->total, 2, '.', ''),
                        'unit' => $tvitem->producto->unit->code,
                        'codetypeprice' => $tvitem->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
                        'afectacion' => $afectacion,
                        'codeafectacion' => $tvitem->gratuito ? '9996' : $codeafectacion,
                        'nameafectacion' => $tvitem->gratuito ? 'GRA' : $nameafectacion,
                        'typeafectacion' => $tvitem->gratuito ? 'FRE' : $typeafectacion,
                        'abreviatureafectacion' => $tvitem->gratuito ? 'Z' : $abreviatureafectacion,
                        'percent' => $tvitem->igv > 0 ? $percent : 0,
                    ]);
                    $counter++;
                }
            }
        }
    }
}
