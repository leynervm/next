<?php

namespace Database\Seeders;

use App\Models\Concept;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Concept::create([
            'name' => 'VENTAS',
            'default' => 1,
        ]);

        Concept::create([
            'name' => 'INTERNET',
            'default' => 2,
        ]);

        Concept::create([
            'name' => 'PAGO CUOTA',
            'default' => 3,
        ]);
    }
}
