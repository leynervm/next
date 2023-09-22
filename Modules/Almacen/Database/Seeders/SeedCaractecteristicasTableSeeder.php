<?php

namespace Modules\Almacen\Database\Seeders;

use App\Models\Caracteristica;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedCaractecteristicasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $pantalla = Caracteristica::create([
            'name' => 'PANTALLA'
        ]);

        $pantalla->especificacions()->createMany([
            ['name' => '15"'],
            ['name' => '14.5"'],
            ['name' => '16"'],
        ]);

        $procesador = Caracteristica::create([
            'name' => 'PROCESADOR'
        ]);

        $procesador->especificacions()->createMany([
            ['name' => 'core i3 5th Gen.'],
            ['name' => 'core i3 6th Gen.'],
            ['name' => 'core i3 7th Gen.'],
            ['name' => 'core i3 8th Gen.'],
            ['name' => 'core i3 9th Gen.'],
            ['name' => 'core i5 10th Gen.'],
            ['name' => 'core i5 5th Gen.'],
            ['name' => 'core i5 6th Gen.'],
            ['name' => 'core i5 7th Gen.'],
            ['name' => 'core i5 8th Gen.'],
            ['name' => 'core i5 9th Gen.'],
            ['name' => 'core i7 5th Gen.'],
            ['name' => 'core i7 6th Gen.'],
            ['name' => 'core i7 7th Gen.'],
        ]);

        $almacenamiento = Caracteristica::create([
            'name' => 'ALMACENAMIENTO'
        ]);

        $almacenamiento->especificacions()->createMany([
            ['name' => 'SSD 120 GB'],
            ['name' => 'SSD 240 GB'],
            ['name' => 'SSD 260 GB'],
            ['name' => 'SSD 420 GB'],
            ['name' => 'SSD 480 GB'],
            ['name' => 'M2 120 GB'],
            ['name' => 'M2 240 GB'],
            ['name' => 'HDD 500 GB'],
            ['name' => 'HDD 1 TB'],
            ['name' => 'HDD 480 GB'],
        ]);

        $ram = Caracteristica::create([
            'name' => 'MEMORIA RAM'
        ]);

        $ram->especificacions()->createMany([
            ['name' => 'DDR5 8GB'],
            ['name' => 'DDR4 8GB'],
            ['name' => 'DDR4 16GB'],
            ['name' => 'DDR3 4GB'],
            ['name' => 'DDR3 2GB'],
        ]);

    }
}
