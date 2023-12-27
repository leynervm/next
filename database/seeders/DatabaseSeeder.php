<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Almacen\Database\Seeders\AlmacenDatabaseSeeder;
use Modules\Almacen\Database\Seeders\SeedEstantesTableSeeder;
use App\Models\Producto;
use Modules\Facturacion\Database\Seeders\FacturacionDatabaseSeeder;
use Nwidart\Modules\Facades\Module;

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
        $this->call(TypecomprobanteSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(MonedaSeeder::class);
        $this->call(CuentaSeeder::class);
        $this->call(EquipoSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(RangoSeeder::class);
        $this->call(PricetypeSeeder::class);
        $this->call(TypepaymentSeeder::class);
        // $this->call(MotivotrasladoSeeder::class);
        $this->call(ProveedortypeSeeder::class);

        $this->call(ConceptSeeder::class);
        $this->call(MethodpaymentSeeder::class);

        $this->call(CaracteristicaSeeder::class);
        $this->call(CategorySeeder::class);
        

        if (Module::isEnabled('Facturacion')) {
            $this->call(FacturacionDatabaseSeeder::class);
        }


        if (Module::isEnabled('Ventas') || Module::isEnabled('Almacen')) {
            Storage::deleteDirectory('productos');
            Storage::makeDirectory('productos');

            $this->call(SeedEstantesTableSeeder::class);
            Producto::factory(10)->create();
            $this->call(ProductoSeeder::class);
        }

        if (Module::isEnabled('Almacen')) {
            $this->call(AlmacenDatabaseSeeder::class);
        }

    }
}
