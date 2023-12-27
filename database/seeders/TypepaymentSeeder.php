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
        Typepayment::create([
            'name' => 'Contado',
            'paycuotas' => 0,
            'default' => 1,
        ]);

        Typepayment::create([
            'name' => 'Credito',
            'paycuotas' => 1,
            'default' => 0,
        ]);
    }
}
