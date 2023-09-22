<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'code' => 'NIU',
            'name' => 'UND',
        ]);

        Unit::create([
            'code' => 'ZZ',
            'name' => 'SERVICIO',
        ]);

        Unit::create([
            'code' => 'MTR',
            'name' => 'METRO',
        ]);

        Unit::create([
            'code' => 'CEN',
            'name' => 'CIENTO DE UNIDADES',
        ]);
    }
}
