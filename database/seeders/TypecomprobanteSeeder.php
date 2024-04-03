<?php

namespace Database\Seeders;

use App\Models\Typecomprobante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypecomprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Typecomprobante::firstOrCreate([
            'code' => 'VT',
            'name' => 'TICKET VENTA',
            'descripcion' => 'TICKET VENTA',
            'sendsunat' => 0,
        ]);
    }
}
