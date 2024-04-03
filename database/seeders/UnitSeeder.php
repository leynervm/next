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
        Unit::firstOrCreate([
            'code' => 'NIU',
            'name' => 'UND',
        ]);

        Unit::firstOrCreate([
            'code' => 'ZZ',
            'name' => 'SERVICIO',
        ]);

        Unit::firstOrCreate([
            'code' => 'MTR',
            'name' => 'METRO',
        ]);

        Unit::firstOrCreate([
            'code' => 'CEN',
            'name' => 'CIENTO DE UNIDADES',
        ]);
    }
}
