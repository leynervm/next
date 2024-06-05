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
            // 'icono' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            //                 stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
            //                 class="w-8 h-8 block text-white">
            //                 <path d="M5 14.5C5 14.5 6.5 14.5 8.5 18C8.5 18 14.0588 8.83333 19 7" />
            //             </svg>',
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
