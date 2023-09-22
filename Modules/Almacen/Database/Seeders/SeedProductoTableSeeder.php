<?php

namespace Modules\Almacen\Database\Seeders;

use App\Models\Serie;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Producto;
use Faker\Factory as Faker;

class SeedProductoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */


    public function run()
    {
        Model::unguard();
        $faker = Faker::create();

        // $this->call("OthersTableSeeder");
        $productos = Producto::all();
        $data = Almacen::all()->pluck('id');

        foreach ($productos as $producto) {

            $almacens = $faker->randomElements($data, rand(1, Almacen::count()));
            $cantidad = rand(1, 100);
            $producto->almacens()->syncWithPivotValues(
                $almacens,
                ['cantidad' => $cantidad]
            );

            if ($cantidad < 40) {
                for ($i = 0; $i < $cantidad; $i++) {
                    for ($j = 1; $j <= count($almacens); $j++) {
                        Serie::create([
                            'serie' =>  $faker->bothify('?##??###?#?#'),
                            'almacen_id' =>  $j,
                            'producto_id' =>  $producto->id,
                            'user_id' =>  1
                        ]);
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
