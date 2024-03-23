<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $almacens = Almacen::all();
        // $stock = rand(0, 100);
        // $productos = Producto::factory(10)->hasAttached(
        //     $almacens,
        //     ['cantidad' => $stock]
        // );
        // $productos = Producto::factory(2000)->hasImages(3)->create();
        $productos = Producto::factory(200)->create();

        DB::select("INSERT INTO productos (name, slug, modelo, pricebuy, pricesale, igv, publicado, code, codefabricante, views, minstock, almacenarea_id, estante_id, marca_id, category_id, subcategory_id, unit_id, user_id) 
            VALUES
            ('MOUSE GAMING REDRAGON STORM ELITE WHITE M988W RGB', 'mouse-gaming-redragon-storm-elite-white-m988w-rgb', 'STORM ELITE WHITE M988W', 23, 35, 0, 1, 'JN0NGAZDA', '6950376779342', 0, 2, NULL, NULL, 9, 2, 2, 1, 1),
            ('IMPRESORA MULTIFUNCIONAL BROTHER INKBENEFIT TANK DCPT720DW CONEXIÓN INALÁMBRICA E IMPRESIÓN DUPLEX CON TANQUES DE TINTA', 'impresora-multifuncional-brother-inkbenefit-tank-dcpt720dw-conexion-inalambrica-e-impresion-duplex-con-tanques-de-tinta', 'DCPT720DW', 2194.4100, 879, 0, 0, 'DYNZ82PLR', '', 0, 1, NULL, NULL, 5, 2, 5, 1, 1),
            ('MOUSE GAMER GAMENOTE MS1033', 'mouse-gamer-gamenote-ms1033', 'MS1033', 12, 20, 0, 1, 'WJN77CTJX', '', 0, 2, NULL, NULL, 10, 6, 1, 1, 1),
            ('TECLADO PARA JUEGOS CON SOPORTE PARA TELÉFONO, ACCESORIO MECÁNICO DE METAL CON RETROILUMINACIÓN RGB, 104 TECLAS, ANTIAGUA', 'teclado-para-juegos-con-soporte-para-telefono-accesorio-mecanico-de-metal-con-retroiluminacion-rgb-104-teclas-antiagua', 'GAMER', 33, 45, 0, 1, 'XRDPDATZN', '', 0, 2, NULL, NULL, 10, 7, 1, 1, 1),
            ('IDEAPAD SLIM 5I 16\" 8VA GEN - CLOUD GREY', 'ideapad-slim-5i-16-8va-gen-cloud-grey', '83BG000ULM', 1599, 1999, 0, 1, 'FWADHHIGY', '83BG000ULM', 0, 1, NULL, NULL, 2, 1, 2, 1, 1),
            ('IDEAPAD GAMING 3 15\" 7MA GEN - ONYX GREY', 'ideapad-gaming-3-15-7ma-gen-onyx-grey', '82SB00MFLM', 3500, 5000, 0, 1, '75OUUSMZV', '82SB00MFLM', 0, 1, NULL, NULL, 2, 1, 1, 1, 1),
            ('LAPTOP GAMER LENOVO LEGION 5 AMD RYZEN 5 8GB RAM 512GB SSD 15.6\" RTX 3050', 'laptop-gamer-lenovo-legion-5-amd-ryzen-5-8gb-ram-512gb-ssd-15-6-rtx-3050', 'LEGION 5', 3999, 4248.9700, 0, 1, 'J5LJANR2Q', '1877162', 0, 0, NULL, NULL, 2, 1, 1, 1, 1),
            ('MOUSE CORSAIR SCIMITAR PRO RGB', 'mouse-corsair-scimitar-pro-rgb', 'PRO RGB', 150, 300, 0, 1, 'JRFK4GZHE', '', 0, 1, NULL, NULL, 11, 6, 1, 1, 1),
            ('MOUSE GAMER REDRAGON CENTROPHORUS M601 RGB', 'mouse-gamer-redragon-centrophorus-m601-rgb', 'M601', 25, 50, 0, 1, '6V7HVV6UP', '', 0, 2, NULL, NULL, 9, 6, 1, 1, 1),
            ('PROCESADOR INTEL CORE I7-13700F, 2.10 GHZ', 'procesador-intel-core-i7-13700f-2-10-ghz', 'CORE I7-13700F', 1800, 2030, 0, 0, 'Y0HI71TMH', '', 0, 2, NULL, NULL, 1, 7, 1, 1, 1),
            ('PROCESADOR AMD RYZEN 3-3200G, 3.60 GHZ', 'procesador-amd-ryzen-3-3200g-3-60-ghz', 'RYZEN 3-3200G', 299, 500, 0, 1, 'KODEQPBXM', '', 0, 2, NULL, NULL, 12, 7, 1, 1, 1),
            ('XVR DAHUA DH-XVR5104HS-4KL-I3, 4CH, 4K, 8MP, 1HDD 10TB', 'xvr-dahua-dh-xvr5104hs-4kl-i3-4ch-4k-8mp-1hdd-10tb', 'DH-XVR5104HS-4KL-I3', 399, 572, 0, 0, 'YAH7HNPMI', '', 0, 2, NULL, NULL, 13, 8, 5, 1, 1),
            ('MONITOR LG 27MQ400-B, 27\" FULL HD', 'monitor-lg-27mq400-b-27-full-hd', '27MQ400-B', 499, 0, 0, 0, '7NGY58WY0', '', 0, 680, NULL, NULL, 15, 3, 2, 1, 1),
            ('CONECTOR DE RED NEXXT RJ45 CAT 5', 'conector-de-red-nexxt-rj45-cat-5', 'RJ45 CAT5', 0.4000, 0, 0, 1, 'O1ULAW1M1', '', 0, 0, NULL, NULL, 17, 7, 2, 1, 1),
            ('AUDÍFONOS GAMER T-DAGGER SONA T-RGH304 NEGRO', 'audifonos-gamer-t-dagger-sona-t-rgh304-negro', 'T-RGH304', 59, 0, 0, 1, 'WC0I5FAIW', '', 0, 2, NULL, NULL, 20, 5, 1, 1, 1)");



        // if (Module::isEnabled('Almacen')) {
        //     if ($stock < 20) {
        //         $productos->has(Serie::factory()->count($stock));
        //     }
        // }
        // $productos->hasImages(3)->create();

        $productos->each(function ($producto) use ($almacens) {
            $stock = rand(3, 30);
            $producto->almacens()->syncWithPivotValues($almacens, [
                'cantidad' => $stock
            ]);

            if (Module::isEnabled('Almacen')) {
                if ($stock < 20) {
                    $producto->almacens()->each(function ($almacen) use ($producto) {
                        for ($i = 0; $i < $almacen->pivot->cantidad; $i++) {
                            $producto->series()->create([
                                'almacen_id' => $almacen->id,
                                'serie' => Str::random(12)
                            ]);
                        }
                    });
                }
            }
        });
    }
}
