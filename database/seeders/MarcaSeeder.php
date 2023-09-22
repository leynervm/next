<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Marca::create([
            'name' => 'LENOVO',
        ]);
        Marca::create([
            'name' => 'ASUS',
        ]);
        Marca::create([
            'name' => 'BROTHER',
        ]);
        Marca::create([
            'name' => 'HP',
        ]);
        Marca::create([
            'name' => 'ACER',
        ]);
    }
}
