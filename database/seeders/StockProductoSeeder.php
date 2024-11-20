<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productos = Producto::select('id', 'slug')->get();
        $almacens = Almacen::get()->pluck('id')->toArray();

        foreach ($productos as $producto) {
            $producto->almacens()->syncWithPivotValues($almacens,  [
                'cantidad' => 100,
            ]);
        }
    }
}
