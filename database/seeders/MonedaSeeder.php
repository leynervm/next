<?php

namespace Database\Seeders;

use App\Models\Moneda;
use App\Models\Tribute;
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
        Moneda::firstOrCreate([
            'currency' => 'SOLES',
            'code' => 'PEN',
            'simbolo' => 'S/.',
            'default' => 1,
        ]);

        Moneda::firstOrCreate([
            'currency' => 'DÓLARES',
            'code' => 'USD',
            'simbolo' => '$.',
        ]);
       
    }
}
