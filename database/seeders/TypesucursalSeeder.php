<?php

namespace Database\Seeders;

use App\Models\Typesucursal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypesucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Typesucursal::firstOrCreate([
            'code' => 'MA',
            'name' => 'Casa Matriz',
            'descripcion' => 'Lugar donde la empresa centraliza las operaciones realizadas por sus sucursales y/o agencias.',
        ]);
        Typesucursal::firstOrCreate([
            'name' => 'Sucursal',
            'code' => 'SU',
            'descripcion' => 'Local descentralizado de la empresa encargado de las operaciones en una determinada ubicación geográfica.',
        ]);
        Typesucursal::firstOrCreate([
            'name' => 'Agencia',
            'code' => 'AG',
            'descripcion' => 'Local de la empresa que debe reportar sus operaciones a la Casa Matriz o Sucursal, de la cual depende.',
        ]);
        Typesucursal::firstOrCreate([
            'name' => 'Local Comercial o de Servicios',
            'code' => 'LO',
            'descripcion' => 'Donde el contribuyente lleva a cabo sus actividades comerciales o de servicios.',
        ]);
        Typesucursal::firstOrCreate([
            'name' => 'Sede Productiva',
            'code' => 'PR',
            'descripcion' => 'Donde se realiza el proceso productivo de los bienes que comercializa la empresa.',
        ]);
        Typesucursal::firstOrCreate([
            'name' => 'Depósito(Almacén) ',
            'code' => 'DE',
            'descripcion' => 'Lugar destinado para almacenar mercaderia.',
        ]);
        Typesucursal::firstOrCreate([
            'name' => 'Oficina Administrativa',
            'code' => 'OF',
            'descripcion' => 'Lugar donde se encuentra la mayor parte de la dirección de la empresa.',
        ]);
    }
}
