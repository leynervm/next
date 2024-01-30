<?php

namespace Modules\Facturacion\Database\Seeders;

use App\Models\Modalidadtransporte;
use App\Models\Motivotraslado;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SeedMotivotrasladosTableSeeder extends Seeder
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

        //Codigo se paso a FacturacionSeeder por 
        //los motivo que estan relacionados a un typecomprbante

    }
}
