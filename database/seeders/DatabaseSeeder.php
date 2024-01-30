<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Almacen\Database\Seeders\AlmacenDatabaseSeeder;

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

        $this->call(UbigeoSeeder::class); //Requerido
        $this->call(TypecomprobanteSeeder::class); //Requerido
        // $this->call(EmpresaSeeder::class);
        $this->call(UserSeeder::class); 
        // $this->call(AreaSeeder::class);
        $this->call(MonedaSeeder::class); //Requerido
        // $this->call(CuentaSeeder::class);
        // $this->call(EquipoSeeder::class);
        // $this->call(MarcaSeeder::class);
        $this->call(UnitSeeder::class); //Requerido
        // $this->call(RangoSeeder::class);
        // $this->call(PricetypeSeeder::class);

        //Para tipocomprobantes sin series
        $this->call(TypepaymentSeeder::class); //Requerido

        //Para tipocomprobantes con series
        // $this->call(SeriecomprobanteSeeder::class);


        // $this->call(ProveedortypeSeeder::class);
        $this->call(ConceptSeeder::class); //Requerido
        // $this->call(MethodpaymentSeeder::class);
        // $this->call(CaracteristicaSeeder::class);
        // $this->call(CategorySeeder::class);


        if (Module::isEnabled('Facturacion')) {
            $this->call(FacturacionDatabaseSeeder::class);
        }


        if (Module::isEnabled('Ventas') || Module::isEnabled('Almacen')) {
            Storage::deleteDirectory('productos');
            Storage::makeDirectory('productos');

            if (Module::isEnabled('Almacen')) {
                $this->call(AlmacenDatabaseSeeder::class);
            }

            // $this->call(ProductoSeeder::class);
        }
    }
}
