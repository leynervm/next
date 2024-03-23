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
        $listafinal = Pricetype::create([
            'name' => 'LISTA FINAL',
            'rounded' => 1,
            'decimals' => 2,
            'default' => 1,
            'web' => 0,
            'defaultlogin' => 1,
        ]);

        $listaweb = Pricetype::create([
            'name' => 'LISTA WEB',
            'rounded' => 1,
            'decimals' => 2,
            'web' => 1,
        ]);

        $listatecnico = Pricetype::create([
            'name' => 'LISTA TECNICO',
            'rounded' => 0,
            'decimals' => 2,
            'web' => 0,
        ]);

        $listadistribucion = Pricetype::create([
            'name' => 'LISTA DISTRIBUCION',
            'rounded' => 0,
            'decimals' => 4,
            'web' => 0,
        ]);

        $rangos = Rango::all();
        $gananciafinal = 60;
        $gananciaweb = 50;
        $gananciatecnico = 30;
        $gananciadistribucion = 15;

        if (count($rangos) > 0) {
            foreach ($rangos as $rango) {

                $rango->pricetypes()->attach([
                    $listafinal->id => [
                        'ganancia' => $gananciafinal
                    ]
                ]);

                $rango->pricetypes()->attach([
                    $listaweb->id => [
                        'ganancia' => $gananciaweb
                    ]
                ]);

                $rango->pricetypes()->attach([
                    $listatecnico->id => [
                        'ganancia' => $gananciatecnico
                    ]
                ]);

                $rango->pricetypes()->attach([
                    $listadistribucion->id => [
                        'ganancia' => $gananciadistribucion
                    ]
                ]);

                $gananciafinal--;
                $gananciaweb--;
                $gananciatecnico--;
                $gananciadistribucion = number_format($gananciadistribucion - 0.5, 2, '.', '');
            }
        }
    }
}
