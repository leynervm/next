<?php

namespace Modules\Facturacion\Database\Seeders;

use App\Models\Typecomprobante;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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

        $factura = Typecomprobante::create([
            'code' => '01',
            'name' => 'FACTURA VENTA',
            'descripcion' => 'FACTURA VENTA',
            'sendsunat' => 1,
        ]);

        $boleta = Typecomprobante::create([
            'code' => '03',
            'name' => 'BOLETA VENTA',
            'descripcion' => 'BOLETA VENTA',
            'sendsunat' => 1,
        ]);

        $notacreditofactura = Typecomprobante::create([
            'code' => '07',
            'name' => 'NOTA DE CREDITO',
            'descripcion' => 'NOTA DE CREDITO QUE ANULA FACTURA',
            'sendsunat' => 1,
        ]);

        $notacreditoboleta = Typecomprobante::create([
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

        $recibo = Typecomprobante::create([
            'code' => '14',
            'name' => 'RECIBO SERVICIOS PUBLICOS',
            'descripcion' => 'RECIBO SERVICIOS PUBLICOS',
            'sendsunat' => 1,
        ]);

        $factura->seriecomprobantes()->createMany([
            [
                'serie' => 'F001',
                'contador' => 0
            ],
            [
                'serie' => 'F002',
                'contador' => 0
            ]
        ]);

        $boleta->seriecomprobantes()->createMany([
            [
                'serie' => 'B001',
                'contador' => 0
            ],
            [
                'serie' => 'B002',
                'contador' => 0
            ]
        ]);

        $notacreditofactura->seriecomprobantes()->createMany([
            [
                'serie' => 'FC01',
                'code' => '01',
                'contador' => 0
            ],
            [
                'serie' => 'FC02',
                'code' => '01',
                'contador' => 0
            ]
        ]);

        $notacreditoboleta->seriecomprobantes()->createMany([
            [
                'serie' => 'BC01',
                'code' => '03',
                'contador' => 0
            ],
            [
                'serie' => 'BC02',
                'code' => '03',
                'contador' => 0
            ],
        ]);

        $guiaremision->seriecomprobantes()->create([
            'serie' => 'TG01',
            'contador' => 0
        ]);

        $recibo->seriecomprobantes()->create([
            'serie' => 'R001',
            'contador' => 0
        ]);
    }
}
