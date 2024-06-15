<?php

namespace Database\Seeders;

use App\Models\Pricetype;
use App\Models\Rango;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PricetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lista1 = Pricetype::create([
            'name' => 'LISTA 1',
            'rounded' => 1,
            'decimals' => 2,
            'default' => 1,
            'web' => 0,
            'defaultlogin' => 1,
            'campo_table' => 'precio_1'
        ]);

        $lista2 = Pricetype::create([
            'name' => 'LISTA 2',
            'rounded' => 1,
            'decimals' => 2,
            'web' => 1,
            'campo_table' => 'precio_2'
        ]);

        $lista3 = Pricetype::create([
            'name' => 'LISTA 3',
            'rounded' => 0,
            'decimals' => 2,
            'web' => 0,
            'campo_table' => 'precio_3'
        ]);

        $lista4 = Pricetype::create([
            'name' => 'LISTA 4',
            'rounded' => 0,
            'decimals' => 3,
            'web' => 0,
            'campo_table' => 'precio_4'
        ]);

        $lista5 = Pricetype::create([
            'name' => 'LISTA 5',
            'rounded' => 0,
            'decimals' => 4,
            'web' => 0,
            'campo_table' => 'precio_5'
        ]);

        $rangos = Rango::all();
        // $incremento2 = 60;
        // $incrementoweb = 50;
        // $incrementotecnico = 30;
        // $incrementodistribucion = 15;

        if (count($rangos) > 0) {
            foreach ($rangos as $rango) {

                $rango->pricetypes()->sync([
                    $lista1->id => [
                        'ganancia' => 0
                    ]
                ]);

                $rango->pricetypes()->sync([
                    $lista2->id => [
                        'ganancia' => 0
                    ]
                ]);

                $rango->pricetypes()->sync([
                    $lista3->id => [
                        'ganancia' => 0
                    ]
                ]);

                $rango->pricetypes()->sync([
                    $lista4->id => [
                        'ganancia' => 0
                    ]
                ]);

                $rango->pricetypes()->sync([
                    $lista5->id => [
                        'ganancia' => 0
                    ]
                ]);

                // $gananciafinal--;
                // $gananciaweb--;
                // $gananciatecnico--;
                // $gananciadistribucion = number_format($gananciadistribucion - 0.5, 2, '.', '');
            }
        }
    }
}
