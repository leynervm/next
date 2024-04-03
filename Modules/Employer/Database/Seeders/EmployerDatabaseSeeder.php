<?php

namespace Modules\Employer\Database\Seeders;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use App\Models\Concept;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class EmployerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (Module::isEnabled('Employer')) {
            Concept::firstOrCreate([
                'name' => 'PAGO MENSUAL PERSONAL',
                'typemovement' => MovimientosEnum::EGRESO->value,
                'default' => DefaultConceptsEnum::PAYEMPLOYER->value,
            ]);

            Concept::firstOrCreate([
                'name' => 'ADELANTO PAGO PERSONAL',
                'typemovement' => MovimientosEnum::EGRESO->value,
                'default' => DefaultConceptsEnum::ADELANTOEMPLOYER->value,
            ]);


            $this->call(SeedRoleTableSeeder::class);
        }
    }
}
