<?php

namespace Modules\Facturacion\Database\Seeders;

use App\Models\Motivotraslado;
use App\Models\Typecomprobante;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TypecomprobanteTableSeeder extends Seeder
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

        Typecomprobante::create([
            'code' => '01',
            'name' => 'FACTURA VENTA',
            'descripcion' => 'FACTURA VENTA',
            'sendsunat' => 1,
        ]);

        Typecomprobante::create([
            'code' => '03',
            'name' => 'BOLETA VENTA',
            'descripcion' => 'BOLETA VENTA',
            'sendsunat' => 1,
        ]);

        Typecomprobante::create([
            'code' => '07',
            'name' => 'NOTA DE CREDITO',
            'descripcion' => 'NOTA DE CREDITO QUE ANULA FACTURA',
            'sendsunat' => 1,
        ]);

        Typecomprobante::create([
            'code' => '07',
            'name' => 'NOTA DE CREDITO',
            'descripcion' => 'NOTA DE CREDITO QUE ANULA BOLETA',
            'sendsunat' => 1,
        ]);

        $guiaremision = Typecomprobante::create([
            'code' => '09',
            'name' => 'GUIA DE REMISIÓN REMITENTE',
            'descripcion' => 'GUIA DE REMISIÓN REMITENTE',
            'sendsunat' => 1,
        ]);

        // Typecomprobante::create([
        //     'code' => '14',
        //     'name' => 'RECIBO SERVICIOS PUBLICOS',
        //     'descripcion' => 'RECIBO SERVICIOS PUBLICOS',
        //     'sendsunat' => 1,
        // ]);


        $guiaremision->motivotraslados()->create([
            'code' => '01',
            'name' => 'VENTA'
        ]);

        $guiaremision->motivotraslados()->create([
            'code' => '02',
            'name' => 'COMPRA'
        ]);

        $guiaremision->motivotraslados()->create([
            'code' => '03',
            'name' => 'VENTA CON ENTREGA A TERCEROS'
        ]);

        $guiaremision->motivotraslados()->create([
            'code' => '04',
            'name' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA'
        ]);

        $guiaremision->motivotraslados()->create([
            'code' => '05',
            'name' => 'CONSIGNACIóN'
        ]);

        $guiaremision->motivotraslados()->create([
            'code' => '06',
            'name' => 'DEVOLUCIÓN'
        ]);

        // $guiaremision->motivotraslados()->create([
        //     'code' => '07',
        //     'name' => 'RECOJO DE BIENES TRANSFORMADOS'
        // ]);

        // $guiaremision->motivotraslados()->create([
        //     'code' => '08',
        //     'name' => 'IMPORTACION'
        // ]);

        // $guiaremision->motivotraslados()->create([
        //     'code' => '09',
        //     'name' => 'EXPORTACION'
        // ]);

        $guiaremision->motivotraslados()->create([
            'code' => '13',
            'name' => 'OTROS'
        ]);

        $guiaremision->motivotraslados()->create([
            'code' => '14',
            'name' => 'VENTA SUJETA A CONFIRMACIÓN DEL COMPRADOR'
        ]);

        // $guiaremision->motivotraslados()->create([
        //     'code' => '18',
        //     'name' => 'TRASLADO EMISOR ITINERANTE CP'
        // ]);

        // $guiaremision->motivotraslados()->create([
        //     'code' => '19',
        //     'name' => 'TRASLADO A ZONA PRIMARIA'
        // ]);
    }
}
