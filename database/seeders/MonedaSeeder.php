<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Equipo;
use App\Models\Marca;
use App\Models\Moneda;
use App\Models\Tribute;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class MonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Moneda::create([
            'currency' => 'SOLES',
            'code' => 'PEN',
            'default' => 1,
            'delete' => 0
        ]);

        Area::create([
            'name' => 'PLANTA INTERNA',
            'slug' => 'planta-interna',
            'visible' => 1,
            'delete' => 0
        ]);
        Area::create([
            'name' => 'PLANTA EXTERNA',
            'slug' => 'planta-externa',
            'visible' => 1,
            'delete' => 0
        ]);

        Marca::create([
            'name' => 'LENOVO',
        ]);
        Marca::create([
            'name' => 'ASUS',
        ]);
        Marca::create([
            'name' => 'BROTHER',
        ]);
        Marca::create([
            'name' => 'HP',
        ]);
        Marca::create([
            'name' => 'ACER',
        ]);

        Equipo::create([
            'name' => 'LAPTOP',
        ]);
        Equipo::create([
            'name' => 'IMPRESORA',
        ]);
        Equipo::create([
            'name' => 'COMPUTADORA',
        ]);
        Equipo::create([
            'name' => 'TABLET',
        ]);

        Tribute::create([
            'tribute' => 'EXO',
            'name' => 'VAT',
            'code' => 9999,
            'abreviature' => 'E',
            'default' => '1'
        ]);

        Unit::create([
            'code' => 'NIU',
            'name' => 'UND',
        ]);
        // Unit::create([
        //     'code' => 'ZZ',
        //     'code' => 'SERVICIO',
        // ]);

    }
}
