<?php

namespace Modules\Marketplace\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Marketplace\Entities\Shipmenttype;

class SeedShipmenttypessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Shipmenttype::firstOrCreate([
            'name' => 'RECOJO EN TIENDA',
            'descripcion' => 'HORARIO DE ATENCIÓN DE 08:00 AM HASTA LAS 08:00 PM, RECLAMAR CON SU COMPROBANTE DE COMPRA',
            'isenvio' => 0,
        ]);

        Shipmenttype::firstOrCreate([
            'name' => 'ENVÍO A DOMICILIO',
            'descripcion' => 'ESPECIFICAR LA DIRECCIÓN DE ENTREGA, APLICA COSTO ADICIONAL PARA EL ENVÍO DE SU PEDIDO',
            'isenvio' => Shipmenttype::ENVIO_DOMICILIO,
        ]);
    }
}
