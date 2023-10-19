<?php

namespace Database\Seeders;

use App\Models\Banco;
use App\Models\Cuenta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $bcp = Banco::create([
            'name' => 'BCP',
        ]);

        $banconacion = Banco::create([
            'name' => 'BANCO DE LA NACIÓN',
        ]);

        $bcp->cuentas()->create([
            'account' => 'CUENTA DE AHORROS BCP',
            'descripcion' => 'CUENTA DE NEXT TECHNOLOGIES',
            'default' => 1,
        ]);

        $bcp->cuentas()->create([
            'account' => 'CUENTA CORRIENTE BCP',
            'descripcion' => 'CUENTA SECUNDARIA DE NEXT TECHNOLOGIES',
        ]);

        $banconacion->cuentas()->create([
            'account' => 'CUENTA AHORROS BANCO NACIONAL',
            'descripcion' => 'CUENTA PRINCIPAL',
            'default' => 1,
        ]);
    }
}
