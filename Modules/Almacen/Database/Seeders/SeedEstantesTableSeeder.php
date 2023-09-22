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
            'name' => 'ESTANTE 1'
        ]);

        Estante::create([
            'name' => 'ESTANTE 2'
        ]);

        Estante::create([
            'name' => 'ESTANTE 3'
        ]);

        Almacenarea::create([
            'name' => 'AREA A'
        ]);

        Almacenarea::create([
            'name' => 'AREA B'
        ]);

        Almacenarea::create([
            'name' => 'AREA C'
        ]);

        Almacenarea::create([
            'name' => 'AREA D'
        ]);

        Almacenarea::create([
            'name' => 'AREA E'
        ]);
    }
}
