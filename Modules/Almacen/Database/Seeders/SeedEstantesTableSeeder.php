<?php

namespace Modules\Almacen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Almacenarea;
use App\Models\Estante;

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

        Estante::firstOrCreate([
            'name' => 'ESTANTE 1'
        ]);

        Estante::firstOrCreate([
            'name' => 'ESTANTE 2'
        ]);

        Estante::firstOrCreate([
            'name' => 'ESTANTE 3'
        ]);

        Almacenarea::firstOrCreate([
            'name' => 'AREA A'
        ]);

        Almacenarea::firstOrCreate([
            'name' => 'AREA B'
        ]);

        Almacenarea::firstOrCreate([
            'name' => 'AREA C'
        ]);

        Almacenarea::firstOrCreate([
            'name' => 'AREA D'
        ]);

        Almacenarea::firstOrCreate([
            'name' => 'AREA E'
        ]);
    }
}
