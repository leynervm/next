<?php

namespace Database\Seeders;

use App\Models\Almacen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

class AlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Module::isEnabled('Almacen')) {
            // CON DATOS, COMENTAR CUANDO SE QUIERE DESDE CERO
            Almacen::firstOrCreate([
                'name' => 'ALMACÉN JAEN',
                'default' => Almacen::DEFAULT
            ]);
            Almacen::firstOrCreate([
                'name' => 'ALMACÉN TRUJILLO',
            ]);
            Almacen::firstOrCreate([
                'name' => 'ALMACÉN BAGUA',
            ]);
            Almacen::firstOrCreate([
                'name' => 'ALMACÉN SAN IGNACIO',
            ]);
        }
    }
}
