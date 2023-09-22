<?php

namespace Modules\Almacen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Almacen\Entities\Producto;

class AlmacenDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $this->call(SeedCaractecteristicasTableSeeder::class);
        $this->call(SeedCategoriesTableSeeder::class);
        $this->call(SeedEstantesTableSeeder::class);
        $this->call(SeedAlmacenTableSeeder::class);

        Storage::deleteDirectory('productos');
        Storage::makeDirectory('productos');
        Producto::factory(50)->create();
        $this->call(SeedProductoTableSeeder::class);
    }
}
