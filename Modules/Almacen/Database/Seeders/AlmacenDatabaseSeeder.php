<?php

namespace Modules\Almacen\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

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
    }
}
