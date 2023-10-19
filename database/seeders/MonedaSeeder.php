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
        Moneda::create([
            'currency' => 'SOLES',
            'code' => 'PEN',
            'simbolo' => 'S/.',
            'default' => 1,
        ]);

        Moneda::create([
            'currency' => 'DÓLARES',
            'code' => 'USD',
            'simbolo' => '$.',
        ]);
       
        Tribute::create([
            'tribute' => 'EXO',
            'name' => 'VAT',
            'code' => 9999,
            'abreviature' => 'E',
            'default' => '1'
        ]);


    }
}
