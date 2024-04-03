<?php

namespace Modules\Facturacion\Database\Seeders;

use App\Models\Modalidadtransporte;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedModalidadtransporteTableSeeder extends Seeder
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

        Modalidadtransporte::firstOrCreate([
            'code' => '01',
            'name' => 'Transporte público'
        ]);

        Modalidadtransporte::firstOrCreate([
            'code' => '02',
            'name' => 'Transporte privado'
        ]);
    }
}
