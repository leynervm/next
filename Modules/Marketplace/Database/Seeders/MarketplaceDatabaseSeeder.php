<?php

namespace Modules\Marketplace\Database\Seeders;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use App\Models\Concept;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class MarketplaceDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        if (Module::isEnabled('Marketplace')) {
            Concept::firstOrCreate([
                'name' => 'PAGO MANUAL VENTA VIRTUAL',
                'typemovement' => MovimientosEnum::INGRESO->value,
                'default' => DefaultConceptsEnum::PAYMANUALORDER->value,
            ]);

            $this->call(SeedTrackingstatesTableSeeder::class); //Requerido
            $this->call(SeedShipmenttypessTableSeeder::class); //Requerido
            $this->call(SeedRoleTableSeeder::class); //Requerido
            // $this->call(SeedOrderTableSeeder::class);
        }
    }
}
