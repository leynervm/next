<?php

namespace Modules\Facturacion\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class FacturacionDatabaseSeeder extends Seeder
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
            // DATA VACIO
            $this->call(SeedRoleTableSeeder::class);
            $this->call(TypecomprobanteTableSeeder::class); //Requerido
            $this->call(SeedModalidadtransporteTableSeeder::class); //Requerido

            //Para tipocomprobantes con series
            // $this->call(SeriecomprobanteTableSeeder::class);
        }
    }
}
