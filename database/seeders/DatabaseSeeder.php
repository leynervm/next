<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Almacen\Database\Seeders\AlmacenDatabaseSeeder;
use Modules\Employer\Database\Seeders\EmployerDatabaseSeeder;
use Modules\Facturacion\Database\Seeders\FacturacionDatabaseSeeder;
use Modules\Marketplace\Database\Seeders\MarketplaceDatabaseSeeder;
use Modules\Ventas\Database\Seeders\VentasDatabaseSeeder;
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

        // DATA VACIO, COMENTAR CODIGO EN ALMACENDATABASESEEDER
        $this->call(UbigeoSeeder::class); //Requerido
        $this->call(TypecomprobanteSeeder::class); //Requerido
        $this->call(MonedaSeeder::class); //Requerido
        $this->call(UnitSeeder::class); //Requerido
        $this->call(TypepaymentSeeder::class); //Requerido
        $this->call(MethodpaymentSeeder::class); //Requerido
        $this->call(ConceptSeeder::class); //Requerido
        $this->call(AlmacenDatabaseSeeder::class); //Requerido, y comentar codigo de crear almacens
        $this->call(FacturacionDatabaseSeeder::class); //Requerido
        $this->call(VentasDatabaseSeeder::class); //Requerido
        $this->call(EmployerDatabaseSeeder::class); //Requerido
        $this->call(MarketplaceDatabaseSeeder::class);

        // USUARIO Y ROLES 100% OBLIGATORIO
        $this->call(UserSeeder::class);
        $this->call(RoleSeeder::class);




        // CON DATA SEEDERS
        // $this->call(UbigeoSeeder::class);
        // $this->call(TypecomprobanteSeeder::class);
        // $this->call(AlmacenDatabaseSeeder::class); //Descomentar codigo de crear almacens
        // $this->call(EmpresaSeeder::class);
        // $this->call(AreaSeeder::class);
        // $this->call(MonedaSeeder::class);
        // $this->call(EquipoSeeder::class);
        // $this->call(MarcaSeeder::class);
        // $this->call(UnitSeeder::class);
        // $this->call(PricetypeSeeder::class);
        // $this->call(TypepaymentSeeder::class);
        // $this->call(ProveedortypeSeeder::class);
        // $this->call(ClientSeedeer::class);
        // $this->call(ConceptSeeder::class);
        // $this->call(MethodpaymentSeeder::class);
        // $this->call(CaracteristicaSeeder::class);
        // $this->call(FacturacionDatabaseSeeder::class);
        // $this->call(VentasDatabaseSeeder::class);
        // $this->call(EmployerDatabaseSeeder::class);
        // $this->call(MarketplaceDatabaseSeeder::class);

        // if (Module::isEnabled('Ventas') || Module::isEnabled('Almacen')) {
        //     $this->call(SubcategorySeeder::class);
        //     $this->call(CategorySeeder::class);
        //     $this->call(ProductoSeeder::class);
        // }

    }
}
