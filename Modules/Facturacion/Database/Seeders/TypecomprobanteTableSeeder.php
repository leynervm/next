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

        Typecomprobante::firstOrCreate([
            'code' => '01',
            'name' => 'FACTURA VENTA',
            'descripcion' => 'FACTURA VENTA',
            'sendsunat' => Typecomprobante::SENDSUNAT,
        ]);

        Typecomprobante::firstOrCreate([
            'code' => '03',
            'name' => 'BOLETA VENTA',
            'descripcion' => 'BOLETA VENTA',
            'sendsunat' => Typecomprobante::SENDSUNAT,
        ]);

        Typecomprobante::firstOrCreate([
            'code' => '07',
            'name' => 'NOTA DE CREDITO',
            'descripcion' => 'NOTA DE CREDITO QUE ANULA FACTURA',
            'referencia' => '01',
            'sendsunat' => Typecomprobante::SENDSUNAT,
        ]);

        Typecomprobante::firstOrCreate([
            'code' => '07',
            'name' => 'NOTA DE CREDITO',
            'descripcion' => 'NOTA DE CREDITO QUE ANULA BOLETA',
            'referencia' => '03',
            'sendsunat' => Typecomprobante::SENDSUNAT,
        ]);

        $guiaremision = Typecomprobante::firstOrCreate([
            'code' => '09',
            'name' => 'GUIA DE REMISIÓN REMITENTE',
            'descripcion' => 'GUIA DE REMISIÓN REMITENTE',
            'sendsunat' => Typecomprobante::SENDSUNAT,
        ]);

        Typecomprobante::firstOrCreate([
            'code' => '09',
            'name' => 'GUIA INTERNA',
            'descripcion' => 'GUIA INTERNA',
            'sendsunat' => 0,
        ]);


        // Typecomprobante::create([
        //     'code' => '14',
        //     'name' => 'RECIBO SERVICIOS PUBLICOS',
        //     'descripcion' => 'RECIBO SERVICIOS PUBLICOS',
        //     'sendsunat' => 1,
        // ]);


        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '01',
            'name' => 'VENTA'
        ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '02',
            'name' => 'COMPRA'
        ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '03',
            'name' => 'VENTA CON ENTREGA A TERCEROS'
        ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '04',
            'name' => 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA'
        ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '05',
            'name' => 'CONSIGNACIóN'
        ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '06',
            'name' => 'DEVOLUCIÓN'
        ]);

        // $guiaremision->motivotraslados()->firstOrCreate([
        //     'code' => '07',
        //     'name' => 'RECOJO DE BIENES TRANSFORMADOS'
        // ]);

        // $guiaremision->motivotraslados()->firstOrCreate([
        //     'code' => '08',
        //     'name' => 'IMPORTACION'
        // ]);

        // $guiaremision->motivotraslados()->firstOrCreate([
        //     'code' => '09',
        //     'name' => 'EXPORTACION'
        // ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '13',
            'name' => 'OTROS'
        ]);

        $guiaremision->motivotraslados()->firstOrCreate([
            'code' => '14',
            'name' => 'VENTA SUJETA A CONFIRMACIÓN DEL COMPRADOR'
        ]);

        // $guiaremision->motivotraslados()->firstOrCreate([
        //     'code' => '18',
        //     'name' => 'TRASLADO EMISOR ITINERANTE CP'
        // ]);

        // $guiaremision->motivotraslados()->firstOrCreate([
        //     'code' => '19',
        //     'name' => 'TRASLADO A ZONA PRIMARIA'
        // ]);
    }
}
