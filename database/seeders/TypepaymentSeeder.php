<?php

namespace Database\Seeders;

use App\Models\Typepayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypepaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Typepayment::firstOrCreate([
            'name' => 'Contado',
            'paycuotas' => 0,
            'status' => Typepayment::ACTIVO,
            'default' => Typepayment::DEFAULT,
        ]);

        Typepayment::firstOrCreate([
            'name' => 'Credito',
            'paycuotas' => Typepayment::CREDITO,
            'status' => Typepayment::ACTIVO,
            'default' => 0,
        ]);
    }
}
