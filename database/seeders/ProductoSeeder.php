<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Producto;
use App\Models\Serie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Nwidart\Modules\Facades\Module;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();

        $productos = Producto::factory(10)->create();
        $almacens = Almacen::all();

        foreach ($productos as $producto) {
            if (count($almacens) > 0) {

                $almacenRandom = $almacens->random()->id;
                $cantidad = rand(1, 20);
                $producto->almacens()->syncWithPivotValues($almacenRandom, [
                    'cantidad' => $cantidad
                ]);

                if ($cantidad < 10) {
                    if (Module::isEnabled('Almacen')) {
                        $createserie = $faker->boolean;
                        if ($createserie) {
                            for ($i = 0; $i < $cantidad; $i++) {
                                Serie::create([
                                    'serie' => $faker->bothify('?##??###?#?#'),
                                    'almacen_id' => $almacenRandom,
                                    'producto_id' => $producto->id,
                                    'user_id' => 1
                                ]);
                            }
                        }
                    }
                }
            }

            $aleatorio = rand(1, 2);
            for ($i = 0; $i < $aleatorio; $i++) {
                $image = $faker->image('public/storage/productos', 640, 480, null, false);
                $producto->images()->create([
                    'url' => $image,
                    'default' => $i == 0 ? 1 : 0
                ]);
            }
        }
    }
}
