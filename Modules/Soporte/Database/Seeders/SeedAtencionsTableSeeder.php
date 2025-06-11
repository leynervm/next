<?php

namespace Modules\Soporte\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Condition;
use Modules\Soporte\Entities\Entorno;
use Modules\Soporte\Entities\Estate;
use Modules\Soporte\Entities\Priority;

class SeedAtencionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Priority::firstOrCreate([
            'name' => 'BAJA',
            'color' => '#0bcb92',
        ]);
        Priority::firstOrCreate([
            'name' => 'MEDIA',
            'color' => '#8a8a8a',
        ]);
        Priority::firstOrCreate([
            'name' => 'ALTA',
            'color' => '#ff4242',
        ]);


        Entorno::firstOrCreate([
            'name' => 'DOA',
            'requiredirection' => 1,
            'default' => 0
        ]);
        Entorno::firstOrCreate([
            'name' => 'IN SITE',
            'default' => 1
        ]);


        Estate::firstOrCreate([
            'name' => 'REGISTRADO',
            'color' => '#CCCCCC',
            'default' => 1
        ]);



        $servicioTecnico = Atencion::firstOrCreate([
            'name' => 'SERVICIO TECNICO',
            'equipamentrequire' => 1,
        ]);
        $servicioTecnico->areaworks()->attach([1, 2]);
        $servicioTecnico->entornos()->attach([1]);

        $averia = Atencion::firstOrCreate([
            'name' => 'AVERÍA',
            'equipamentrequire' => 0,
        ]);
        $averia->entornos()->attach([1, 2]);
        $averia->areaworks()->attach([1, 2]);

        $seguridad = Atencion::firstOrCreate([
            'name' => 'SEGURIDAD ELECTRÓNICA',
            'equipamentrequire' => 0,
        ]);
        $seguridad->entornos()->attach([1]);
        $seguridad->areaworks()->attach([2]);

        $redes = Atencion::firstOrCreate([
            'name' => 'REDES Y TELECOMUNICACIONES',
            'equipamentrequire' => 0,
        ]);
        $redes->entornos()->attach([1]);
        $redes->areaworks()->attach([2]);

        Condition::firstOrCreate([
            'name' => 'GARANTIA MARCA',
        ]);
        Condition::firstOrCreate([
            'name' => 'GARANTIA TIENDA',
        ]);
        Condition::firstOrCreate([
            'name' => 'FACTURABLE',
            'flagpagable' => 1,
        ]);
        Condition::firstOrCreate([
            'name' => 'CONTRATO',
            'flagpagable' => 1,
        ]);
        // $this->call("OthersTableSeeder");
    }
}
