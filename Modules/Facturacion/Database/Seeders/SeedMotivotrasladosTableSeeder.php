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
        Modalidadtransporte::create([
            'code' => '01',
            'name' => 'Transporte público'
        ]);

        Modalidadtransporte::create([
            'code' => '02',
            'name' => 'Transporte privado'
        ]);



        Motivotraslado::create([
            'code' => '01',
            'name' => 'VENTA'
        ]);

        Motivotraslado::create([
            'code' => '02',
            'name' => 'COMPRA'
        ]);

        Motivotraslado::create([
            'code' => '03',
            'name' => 'VENTA CON ENTREGA A TERCEROS'
        ]);

        Motivotraslado::create([
            'code' => '04',
            'name' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA'
        ]);

        Motivotraslado::create([
            'code' => '05',
            'name' => 'CONSIGNACIóN'
        ]);

        Motivotraslado::create([
            'code' => '06',
            'name' => 'DEVOLUCIÓN'
        ]);

        Motivotraslado::create([
            'code' => '07',
            'name' => 'RECOJO DE BIENES TRANSFORMADOS'
        ]);

        Motivotraslado::create([
            'code' => '08',
            'name' => 'IMPORTACION'
        ]);

        Motivotraslado::create([
            'code' => '09',
            'name' => 'EXPORTACION'
        ]);

        Motivotraslado::create([
            'code' => '13',
            'name' => 'OTROS'
        ]);

        Motivotraslado::create([
            'code' => '14',
            'name' => 'VENTA SUJETA A CONFIRMACIÓN DEL COMPRADOR'
        ]);

        Motivotraslado::create([
            'code' => '18',
            'name' => 'TRASLADO EMISOR ITINERANTE CP'
        ]);

        Motivotraslado::create([
            'code' => '19',
            'name' => 'TRASLADO A ZONA PRIMARIA'
        ]);

    }
}
