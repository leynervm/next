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

        Priority::create([
            'name' => 'BAJA',
            'color' => '#0bcb92',
            'delete' => 0
        ]);

        Priority::create([
            'name' => 'MEDIA',
            'color' => '#8a8a8a',
            'delete' => 0
        ]);

        Priority::create([
            'name' => 'ALTA',
            'color' => '#ff4242',
            'delete' => 0
        ]);


        Entorno::create([
            'name' => 'DOA',
            'requiredirection' => 1,
            'default' => 0
        ]);

        Entorno::create([
            'name' => 'TALLER',
            'requiredirection' => 0,
            'default' => 1
        ]);


        Estate::create([
            'name' => 'PENDIENTE',
            'finish' => 0,
            'color' => '#CCCCCC',
            'default' => 1
        ]);

        Estate::create([
            'name' => 'DIAGNOSTICO',
            'finish' => 0,
            'color' => '#AAAAAA',
            'default' => 0
        ]);

        
        $servicioTecnico = Atencion::create([
            'name' => 'SERVICIO TECNICO',
            'equipamentrequire' => 1,
        ]);
        $servicioTecnico->areas()->attach([1, 2]);
        $servicioTecnico->entornos()->attach([1]);

        $averia = Atencion::create([
            'name' => 'AVERÍA',
            'equipamentrequire' => 0,
        ]);
        $averia->entornos()->attach([1, 2]);
        $averia->areas()->attach([1, 2]);

        $seguridad = Atencion::create([
            'name' => 'SEGURIDAD ELECTRÓNICA',
            'equipamentrequire' => 0,
        ]);
        $seguridad->entornos()->attach([1]);
        $seguridad->areas()->attach([2]);

        $redes = Atencion::create([
            'name' => 'REDES Y TELECOMUNICACIONES',
            'equipamentrequire' => 0,
        ]);
        $redes->entornos()->attach([1]);
        $redes->areas()->attach([2]);

        Condition::create([
            'name' => 'GARANTIA MARCA',
            'flagpagable' => 0,
        ]);

        Condition::create([
            'name' => 'GARANTIA TIENDA',
            'flagpagable' => 0,
        ]);

        Condition::create([
            'name' => 'FACTURABLE',
            'flagpagable' => 1,
        ]);

        Condition::create([
            'name' => 'CONTRATO',
            'flagpagable' => 1,
        ]);

        // $this->call("OthersTableSeeder");

    }
}
