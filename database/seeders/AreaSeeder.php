<?php

namespace Database\Seeders;

use App\Models\Area;
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
        Area::create([
            'name' => 'PLANTA INTERNA',
            'slug' => 'planta-interna',
            'visible' => 1,
        ]);
        
        Area::create([
            'name' => 'PLANTA EXTERNA',
            'slug' => 'planta-externa',
            'visible' => 1,
        ]);

        Area::create([
            'name' => 'DESARROLLO SOFTWARE & DISEÑO GRÁFICO',
            'slug' => 'desarrollo-software-&-diseño-grafico',
            'visible' => 1,
        ]);

        Area::create([
            'name' => 'VENTAS',
            'slug' => 'ventas',
            'visible' => 1,
        ]);
    }
}
