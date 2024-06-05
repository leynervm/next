<?php

namespace Modules\Marketplace\Database\Seeders;

use App\Models\Sucursal;
use App\Models\Ubigeo;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as FakerFactory;
use Luecano\NumeroALetras\NumeroALetras;
use Modules\Marketplace\Entities\Trackingstate;
use Modules\Marketplace\Entities\Ventaonline;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class SeedVentaonlinesTableSeeder extends Seeder
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

        // $tvitems = TvitemMarketplace::factory()->count(3)->for(
        //     Ventaonline::factory(),
        //     'tvitemable'
        // )->create();

        // SERIECOMPROBANTES
        // Sucursal::first()->seriecomprobantes()->createMany([
        //     [
        //         'serie' => 'F001',
        //         'contador' => 0,
        //         'default' => 0,
        //         'typecomprobante_id' => 2,
        //     ],
        //     [
        //         'serie' => 'B001',
        //         'contador' => 0,
        //         'default' => 0,
        //         'typecomprobante_id' => 3,
        //     ]
        // ]);


        // $ventaonlines = Venta::factory(30)
        //     ->hasTvitemonlines(3)->create();

        // $percent = mi_empresa()->igv;
        // foreach ($ventaonlines as $item) {
        //     if ($item->shipmenttype->isEnviodomicilio()) {
        //         $item->shipment()->create([
        //             'date' => now(),
        //             'direccion' => $faker->address(),
        //             'referencia' => $faker->paragraph(1),
        //             'ubigeo_id' => Ubigeo::all()->random()->id
        //         ]);

        //         $item->trackings()->createMany([
        //             [
        //                 'date' => now(),
        //                 'descripcion' => $faker->paragraph(2),
        //                 'trackingstate_id' => Trackingstate::default()->first()->id,
        //                 'user_id' => 1
        //             ],
        //             [
        //                 'date' => now(),
        //                 'descripcion' => $faker->paragraph(2),
        //                 'trackingstate_id' => 2,
        //                 'user_id' => 1
        //             ],
        //             [
        //                 'date' => now(),
        //                 'descripcion' => $faker->paragraph(1),
        //                 'trackingstate_id' => 3,
        //                 'user_id' => 1
        //             ],
        //             [
        //                 'date' => now(),
        //                 'descripcion' => $faker->paragraph(1),
        //                 'trackingstate_id' => 4,
        //                 'user_id' => 1
        //             ]
        //         ]);
        //     } else {
        //         $item->trackings()->create([
        //             'date' => now(),
        //             'descripcion' => $faker->paragraph(2),
        //             'trackingstate_id' => Trackingstate::default()->first()->id,
        //             'user_id' => 1,
        //         ]);
        //     }

        //     if (Module::isEnabled('Facturacion')) {
        //         if ($item->seriecomprobante->typecomprobante->isSunat()) {
        //             $leyenda = new NumeroALetras();
        //             $comprobante = $item->comprobante()->create([
        //                 'seriecompleta' => $item->seriecompleta,
        //                 'date' =>  $item->date,
        //                 'expire' => $item->date,
        //                 'direccion' => $item->direccion,
        //                 'exonerado' => number_format($item->exonerado, 3, '.', ''),
        //                 'gravado' => number_format($item->gravado, 3, '.', ''),
        //                 'gratuito' => number_format($item->gratuito, 3, '.', ''),
        //                 'inafecto' => number_format($item->inafecto, 3, '.', ''),
        //                 'descuento' => number_format($item->descuentos, 3, '.', ''),
        //                 'otros' => number_format($item->otros, 3, '.', ''),
        //                 'igv' => number_format($item->igv, 3, '.', ''),
        //                 'igvgratuito' => number_format($item->igvgratuito, 3, '.', ''),
        //                 'subtotal' => number_format($item->subtotal, 3, '.', ''),
        //                 'total' => number_format($item->total, 3, '.', ''),
        //                 'paymentactual' => number_format($item->paymentactual, 4, '.', ''),
        //                 'percent' => $percent,
        //                 'referencia' => $item->seriecompleta,
        //                 'leyenda' => $leyenda->toInvoice($item->total, 2, 'NUEVOS SOLES'),
        //                 'client_id' => $item->client_id,
        //                 'typepayment_id' => $item->typepayment_id,
        //                 'seriecomprobante_id' => $item->seriecomprobante_id,
        //                 'moneda_id' => $item->moneda_id,
        //                 'sucursal_id' => $item->sucursal_id,
        //                 'user_id' => $item->user_id,
        //             ]);

        //             $counter = 1;
        //             foreach ($item->tvitems as $tvitem) {
        //                 if ($tvitem->gratuito) {
        //                     $afectacion = $tvitem->igv > 0 ? '15' : '21';
        //                 } else {
        //                     $afectacion = $tvitem->igv > 0 ? '10' : '20';
        //                 }

        //                 $codeafectacion = $tvitem->igv > 0 ? '1000' : '9997';
        //                 $nameafectacion = $tvitem->igv > 0 ? 'IGV' : 'EXO';
        //                 $typeafectacion = $tvitem->igv > 0 ? 'VAT' : 'VAT';
        //                 $abreviatureafectacion = $tvitem->igv > 0 ? 'S' : 'E';

        //                 $comprobante->facturableitems()->create([
        //                     'item' => $counter,
        //                     'descripcion' => $tvitem->producto->name,
        //                     'code' => $tvitem->producto->code,
        //                     'cantidad' => $tvitem->cantidad,
        //                     'price' => number_format($tvitem->price, 2, '.', ''),
        //                     'igv' => number_format($tvitem->igv, 2, '.', ''),
        //                     'subtotaligv' => number_format($tvitem->subtotaligv, 2, '.', ''),
        //                     'subtotal' => number_format($tvitem->subtotal, 2, '.', ''),
        //                     'total' => number_format($tvitem->total, 2, '.', ''),
        //                     'unit' => $tvitem->producto->unit->code,
        //                     'codetypeprice' => $tvitem->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
        //                     'afectacion' => $afectacion,
        //                     'codeafectacion' => $tvitem->gratuito ? '9996' : $codeafectacion,
        //                     'nameafectacion' => $tvitem->gratuito ? 'GRA' : $nameafectacion,
        //                     'typeafectacion' => $tvitem->gratuito ? 'FRE' : $typeafectacion,
        //                     'abreviatureafectacion' => $tvitem->gratuito ? 'Z' : $abreviatureafectacion,
        //                     'percent' => $tvitem->igv > 0 ? $percent : 0,
        //                 ]);
        //                 $counter++;
        //             }
        //         }
        //     }
        // }
    }
}
