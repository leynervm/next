<?php

namespace Modules\Facturacion\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

        // $this->call("OthersTableSeeder");
        //Para tipocomprobantes sin series
        $this->call(TypecomprobanteTableSeeder::class);
        
        //Para tipocomprobantes con series
        // $this->call(SeriecomprobanteTableSeeder::class);

        $this->call(SeedMotivotrasladosTableSeeder::class); //Requerido
        $this->call(SeedModalidadtransporteTableSeeder::class); //Requerido
    }
}
