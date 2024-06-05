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
            'type' => Methodpayment::EFECTIVO,
            'default' => Methodpayment::DEFAULT,
        ]);

        $transferencia = Methodpayment::create([
            'name' => 'TRANSFERENCIA',
            'type' => Methodpayment::TRANSFERENCIA,
        ]);

        // $yape = Methodpayment::create([
        //     'name' => 'YAPE',
        //     'type' => Methodpayment::TRANSFERENCIA,
        // ]);

        // $plin = Methodpayment::create([
        //     'name' => 'PLIN',
        //     'type' => Methodpayment::TRANSFERENCIA,
        // ]);

        // $paypal = Methodpayment::create([
        //     'name' => 'PAYPAL',
        //     'type' => Methodpayment::TRANSFERENCIA,
        // ]);
    }
}
