<?php

namespace Modules\Almacen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Almacen\Entities\Almacen;

class SeedAlmacenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // Almacen::factory(2)->create();

        Almacen::create([
            'name' => 'ALMACÉN JAÉN'
        ]);

        Almacen::create([
            'name' => 'ALMACÉN TRUJILLO'
        ]);
    }
}
