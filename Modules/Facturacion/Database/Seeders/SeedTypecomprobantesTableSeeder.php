<?php

namespace Modules\Facturacion\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Facturacion\Entities\Typecomprobante;

class SeedTypecomprobantesTableSeeder extends Seeder
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

        $factura = Typecomprobante::create([
            'code' => '01',
            'serie' => 'F002',
            'descripcion' => 'FACTURA VENTA',
            'default' => 0,
        ]);

        $boleta = Typecomprobante::create([
            'code' => '03',
            'serie' => 'B002',
            'descripcion' => 'BOLETA VENTA',
            'default' => 1,
        ]);

        $ticket = Typecomprobante::create([
            'code' => 'TV',
            'serie' => 'TV02',
            'descripcion' => 'TICKET VENTA',
            'default' => 0,
        ]);
    }
}
