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
            DB::select("INSERT INTO almacens (id, name) 
            VALUES
                (1, 'ALMACÉN JAEN'),
                (2, 'ALMACÉN TRUJILLO'),
                (3, 'ALMACÉN BAGUA'),        
                (4, 'ALMACÉN SAN IGNACIO')");
        } else if (Module::isEnabled('Ventas')) {
            DB::select("INSERT INTO almacens (id, name) 
            VALUES (1, 'ALMACÉN PRINCIPAL')");
        }
    }
}
