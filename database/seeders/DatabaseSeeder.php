<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Almacen\Database\Seeders\AlmacenDatabaseSeeder;
use Modules\Almacen\Database\Seeders\ProductoSeederTableSeeder;
use Modules\Facturacion\Database\Seeders\FacturacionDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Storage::deleteDirectory('marcas');
        Storage::makeDirectory('marcas');

        Storage::deleteDirectory('equipos');
        Storage::makeDirectory('equipos');

        $this->call(UbigeoSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(MonedaSeeder::class);
        $this->call(EquipoSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(RangoSeeder::class);
        $this->call(PricetypeSeeder::class);
        $this->call(CajaSeeder::class);
        $this->call(TypepaymentSeeder::class);

        $this->call(ConceptSeeder::class);
        $this->call(MethodpaymentSeeder::class);
        
        $this->call(FacturacionDatabaseSeeder::class);
        $this->call(AlmacenDatabaseSeeder::class);
        

        

    }
}
