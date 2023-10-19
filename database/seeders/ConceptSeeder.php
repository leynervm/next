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
            'name' => 'VENTA DIRECTA',
            'default' => Concept::VENTAS,
        ]);

        Concept::create([
            'name' => 'PAGO INTERNET',
            'default' => Concept::INTERNET,
        ]);

        Concept::create([
            'name' => 'PAGO CUOTA',
            'default' => Concept::PAYCUOTA,
        ]);

        Concept::create([
            'name' => 'PAGO COMPRA',
            'default' => Concept::COMPRA,
        ]);

        Concept::create([
            'name' => 'PAGO CUOTA COMPRA',
            'default' => Concept::PAYCUOTACOMPRA,
        ]);
    }
}
