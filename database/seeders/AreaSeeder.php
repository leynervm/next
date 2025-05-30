<?php

namespace Database\Seeders;

use App\Models\Areawork;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Areawork::firstOrCreate([
            'name' => 'PLANTA INTERNA',
            'slug' => 'planta-interna',
            'visible' => 1,
        ]);
        
        Areawork::firstOrCreate([
            'name' => 'PLANTA EXTERNA',
            'slug' => 'planta-externa',
            'visible' => 1,
        ]);

        Areawork::firstOrCreate([
            'name' => 'DESARROLLO SOFTWARE & DISEÑO GRÁFICO',
            'slug' => 'desarrollo-software-&-diseño-grafico',
            'visible' => 1,
        ]);

        Areawork::firstOrCreate([
            'name' => 'VENTAS',
            'slug' => 'ventas',
            'visible' => 1,
        ]);
    }
}
