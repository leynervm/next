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
            'default' => 1,
        ]);

        Typepayment::firstOrCreate([
            'name' => 'Credito',
            'paycuotas' => 1,
            'default' => 0,
        ]);
    }
}
