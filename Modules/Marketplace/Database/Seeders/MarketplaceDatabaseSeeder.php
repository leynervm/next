<?php

namespace Modules\Marketplace\Database\Seeders;

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
            
            $this->call(SeedTrackingstatesTableSeeder::class); //Requerido
            $this->call(SeedShipmenttypessTableSeeder::class); //Requerido

            // $this->call(SeedOrderTableSeeder::class);
        }
    }
}
