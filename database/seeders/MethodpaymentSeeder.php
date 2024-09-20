<?php

namespace Database\Seeders;

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
        $efectivo = Methodpayment::firstOrCreate([
            'name' => 'EFECTIVO',
            'type' => Methodpayment::EFECTIVO,
            'default' => Methodpayment::DEFAULT,
            'definido' => Methodpayment::DEFAULT,
        ]);

        $transferencia = Methodpayment::firstOrCreate([
            'name' => 'TRANSFERENCIA',
            'type' => Methodpayment::TRANSFERENCIA,
            'definido' => Methodpayment::DEFAULT,
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
