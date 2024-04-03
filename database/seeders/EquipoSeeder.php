<?php

namespace Database\Seeders;

use App\Models\Equipo;
use Illuminate\Database\Seeder;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Equipo::firstOrCreate([
            'name' => 'LAPTOP',
        ]);
        Equipo::firstOrCreate([
            'name' => 'IMPRESORA',
        ]);
        Equipo::firstOrCreate([
            'name' => 'COMPUTADORA',
        ]);
        Equipo::firstOrCreate([
            'name' => 'TABLET',
        ]);
    }
}
