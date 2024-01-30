<?php

namespace Database\Seeders;

use App\Models\Typecomprobante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeriecomprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ticket = Typecomprobante::create([
            'code' => 'VT',
            'name' => 'TICKET VENTA',
            'descripcion' => 'TICKET VENTA',
            'sendsunat' => 0,
        ]);

        $guiaremisioninterna = Typecomprobante::create([
            'code' => '09',
            'name' => 'GUIA DE REMISIÓN INTERNA',
            'descripcion' => 'GUIA DE REMISIÓN INTERNA',
            'sendsunat' => 0,
        ]);

        $ticket->seriecomprobantes()->create([
            'serie' => 'VT01',
            'contador' => 0
        ]);

        $guiaremisioninterna->seriecomprobantes()->create([
            'serie' => 'EG07',
            'contador' => 0
        ]);
    }
}
