<?php

namespace Database\Seeders;

use App\Models\Proveedortype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProveedortypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Proveedortype::create([
            'name' => 'PROVEEDOR PRODUCTOS'
        ]);
        
        Proveedortype::create([
            'name' => 'PROVEEDOR SERVICIOS'
        ]);
    }
}
