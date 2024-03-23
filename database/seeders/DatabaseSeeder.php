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
        Storage::deleteDirectory('productos');
        Storage::makeDirectory('productos');

        // DATA VACIO
        $this->call(UbigeoSeeder::class); //Requerido
        $this->call(TypecomprobanteSeeder::class); //Requerido
        $this->call(MonedaSeeder::class); //Requerido
        $this->call(UnitSeeder::class); //Requerido
        $this->call(TypepaymentSeeder::class); //Requerido
        $this->call(ConceptSeeder::class); //Requerido


        // USUARIO Y ROLES 100% OBLIGATORIO
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);

        // CON DATA SEEDERS
        // $this->call(UbigeoSeeder::class);
        // $this->call(TypecomprobanteSeeder::class);
        // $this->call(AlmacenSeeder::class);
        // $this->call(EmpresaSeeder::class);
        // $this->call(AreaSeeder::class);
        // $this->call(MonedaSeeder::class);
        // $this->call(EquipoSeeder::class);
        // $this->call(MarcaSeeder::class);
        // $this->call(UnitSeeder::class);
        // $this->call(RangoSeeder::class);
        // $this->call(PricetypeSeeder::class);
        // $this->call(TypepaymentSeeder::class);
        // $this->call(ProveedortypeSeeder::class);
        // $this->call(ConceptSeeder::class);
        // $this->call(MethodpaymentSeeder::class);
        // $this->call(CaracteristicaSeeder::class);

        // if (Module::isEnabled('Facturacion')) {
        //     $this->call(FacturacionDatabaseSeeder::class);
        // }

        // if (Module::isEnabled('Almacen')) {
        //     $this->call(AlmacenDatabaseSeeder::class);
        // }

        // if (Module::isEnabled('Ventas') || Module::isEnabled('Almacen')) {
        //     $this->call(SubcategorySeeder::class);
        //     $this->call(CategorySeeder::class);
        //     $this->call(ProductoSeeder::class);
        // }
    }
}
