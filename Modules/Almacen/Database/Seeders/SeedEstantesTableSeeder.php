<?php

namespace Modules\Almacen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Almacen\Entities\Almacenarea;
use Modules\Almacen\Entities\Estante;

class SeedEstantesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Estante::create([
            'name' => 'ESTANTE A'
        ]);
        Estante::create([
            'name' => 'ESTANTE C'
        ]);
        Estante::create([
            'name' => 'ESTANTE C'
        ]);

        Almacenarea::create([
            'name' => 'AREA MOUSE'
        ]);
        Almacenarea::create([
            'name' => 'AREA ACCESORIOS'
        ]);
        Almacenarea::create([
            'name' => 'AREA CAMARAS SEGURIDAD'
        ]);
    }
}
