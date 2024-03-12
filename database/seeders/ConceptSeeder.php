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
        Concept::create([
            'name' => 'VENTA DIRECTA',
            'typemovement' => MovimientosEnum::INGRESO->value,
            'default' => DefaultConceptsEnum::VENTAS->value,
        ]);

        Concept::create([
            'name' => 'PAGO INTERNET',
            'typemovement' => MovimientosEnum::INGRESO->value,
            'default' => DefaultConceptsEnum::INTERNET->value,
        ]);

        Concept::create([
            'name' => 'PAGO CUOTA',
            'typemovement' => MovimientosEnum::INGRESO->value,
            'default' => DefaultConceptsEnum::PAYCUOTA->value,
        ]);

        Concept::create([
            'name' => 'PAGO COMPRA',
            'typemovement' => MovimientosEnum::EGRESO->value,
            'default' => DefaultConceptsEnum::COMPRA->value,
        ]);

        Concept::create([
            'name' => 'PAGO CUOTA COMPRA',
            'typemovement' => MovimientosEnum::EGRESO->value,
            'default' => DefaultConceptsEnum::PAYCUOTACOMPRA->value,
        ]);

        Concept::create([
            'name' => 'PAGO MENSUAL PERSONAL',
            'typemovement' => MovimientosEnum::EGRESO->value,
            'default' => DefaultConceptsEnum::PAYEMPLOYER->value,
        ]);

        Concept::create([
            'name' => 'ADELANTO PAGO PERSONAL',
            'typemovement' => MovimientosEnum::EGRESO->value,
            'default' => DefaultConceptsEnum::ADELANTOEMPLOYER->value,
        ]);
    }
}
