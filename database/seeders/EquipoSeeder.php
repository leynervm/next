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
        Equipo::create([
            'name' => 'LAPTOP',
        ]);
        Equipo::create([
            'name' => 'IMPRESORA',
        ]);
        Equipo::create([
            'name' => 'COMPUTADORA',
        ]);
        Equipo::create([
            'name' => 'TABLET',
        ]);
    }
}
