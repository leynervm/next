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
        Typesucursal::firstOrCreate(['code' => 'MA'], [
            'name' => 'Casa Matriz',
            'descripcion' => 'Lugar donde la empresa centraliza las operaciones realizadas por sus sucursales y/o agencias.',
        ]);
        Typesucursal::firstOrCreate(['code' => 'SU'], [
            'name' => 'Sucursal',
            'descripcion' => 'Local descentralizado de la empresa encargado de las operaciones en una determinada ubicación geográfica.',
        ]);
        Typesucursal::firstOrCreate(['code' => 'AG'], [
            'name' => 'Agencia',
            'descripcion' => 'Local de la empresa que debe reportar sus operaciones a la Casa Matriz o Sucursal, de la cual depende.',
        ]);
        Typesucursal::firstOrCreate(['code' => 'LO'], [
            'name' => 'Local Comercial o de Servicios',
            'descripcion' => 'Donde el contribuyente lleva a cabo sus actividades comerciales o de servicios.',
        ]);
        Typesucursal::firstOrCreate(['code' => 'PR'], [
            'name' => 'Sede Productiva',
            'descripcion' => 'Donde se realiza el proceso productivo de los bienes que comercializa la empresa.',
        ]);
        Typesucursal::firstOrCreate(['code' => 'DE'], [
            'name' => 'Depósito(Almacén) ',
            'descripcion' => 'Lugar destinado para almacenar mercaderia.',
        ]);
        Typesucursal::firstOrCreate(['code' => 'OF'], [
            'name' => 'Oficina Administrativa',
            'descripcion' => 'Lugar donde se encuentra la mayor parte de la dirección de la empresa.',
        ]);
    }
}
