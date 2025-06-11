<?php

namespace Modules\Soporte\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Soporte\Entities\Estate;
use Nwidart\Modules\Facades\Module;

class SoporteDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        if (Module::isEnabled('Facturacion')) {
            // Con data
            // $this->call(SeedAtencionsTableSeeder::class);

            Estate::firstOrCreate([
                'name' => 'REGISTRADO',
                'color' => '#ff2323',
                'default' => 1
            ]);
        }
    }
}
