<?php

namespace Modules\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Marketplace\Entities\Trackingstate;

class SeedTrackingstatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // POR DEFECTO DEBE EXISTIR UN TRACKING COMO PREDETERMINADO
        Trackingstate::firstOrCreate([
            'name' => 'REGISTRADO',
        ], [
            'background' => '#ff880a',
            'default' => Trackingstate::DEFAULT
        ]);

        // Trackingstate::firstOrCreate([
        //     'name' => 'EN PROCESO',
        //     'icono' => '',
        //     'background' => '#F0FCDD',
        // ]);

        // Trackingstate::firstOrCreate([
        //     'name' => 'ENVIADO',
        //     'icono' => '',
        //     'background' => '#FF0',
        // ]);

        // Trackingstate::firstOrCreate([
        //     'name' => 'ENTREGADO',
        //     'icono' => '',
        //     'finish' => Trackingstate::FINISH,
        //     'background' => '#F0F',
        // ]);
    }
}
