<?php

namespace Database\Seeders;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
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

        Concept::firstOrCreate([
            'name' => 'PAGO INTERNET',
            'typemovement' => MovimientosEnum::INGRESO->value,
            'default' => DefaultConceptsEnum::INTERNET->value,
        ]);

        Concept::firstOrCreate([
            'name' => 'APERTURA DE CAJA',
            'typemovement' => MovimientosEnum::INGRESO->value,
            'default' => DefaultConceptsEnum::OPENBOX->value,
        ]);
    }
}
