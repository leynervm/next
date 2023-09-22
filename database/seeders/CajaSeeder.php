<?php

namespace Database\Seeders;

use App\Models\Caja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Caja::create([
            'name' => 'CAJA 01',
            'status' => 0
        ]);

        Caja::create([
            'name' => 'CAJA 02',
            'status' => 0
        ]);

        Caja::create([
            'name' => 'CAJA 03',
            'status' => 0
        ]);

        Caja::create([
            'name' => 'CAJA 04',
            'status' => 0
        ]);

        Caja::create([
            'name' => 'CAJA 05',
            'status' => 0
        ]);
    }
}
