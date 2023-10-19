<?php

namespace Database\Seeders;

use App\Models\Cuenta;
use App\Models\Methodpayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MethodpaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $efectivo = Methodpayment::create([
            'name' => 'EFECTIVO',
            'type' => 1,
            'default' => 1,
        ]);

        $transferencia = Methodpayment::create([
            'name' => 'TRANSFERENCIA',
            'type' => 1,
            'default' => 0,
        ]);

        $yape = Methodpayment::create([
            'name' => 'YAPE',
            'type' => 1,
            'default' => 0,
        ]);

        $plin = Methodpayment::create([
            'name' => 'PLIN',
            'type' => 1,
            'default' => 0,
        ]);

        $paypal = Methodpayment::create([
            'name' => 'PAYPAL',
            'type' => 1,
            'default' => 0,
        ]);

        $cuentas = Cuenta::all();
        $transferencia->cuentas()->attach($cuentas->random());
        $paypal->cuentas()->attach($cuentas->random());
        $yape->cuentas()->attach(Cuenta::first());
        $yape->cuentas()->attach(Cuenta::orderBy('id', 'desc')->first());
    }
}
