<?php

namespace Database\Seeders;

use App\Models\Acceso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Acceso::firstOrCreate([
            'access' => Acceso::ACTIVO,
            'limitsucursals' => Acceso::LIMIT_SUCURSALS
        ]);
    }
}
