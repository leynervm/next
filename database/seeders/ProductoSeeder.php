<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Almacen;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = Producto::factory(3000);
        $almacens = Almacen::all();


        foreach ($almacens as $almacen) {
            foreach ($productos as $producto) {
                DB::table('almacen_producto')->insert([
                    'date' =>  now(),
                    'almacen_id' =>  $almacen->id,
                    'producto_id' =>  $producto->id,
                    'cantidad' =>  rand(1, 30),
                    'user_id' =>  1,
                ]);
            }
        }
    }
}
