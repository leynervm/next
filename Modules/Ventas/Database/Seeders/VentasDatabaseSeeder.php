<?php

namespace Modules\Ventas\Database\Seeders;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use App\Models\Almacen;
use App\Models\Concept;
use App\Models\Sucursal;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

class VentasDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PermissionSeeder::class);

        if (Module::isEnabled('Ventas')) {
            Concept::firstOrCreate([
                'name' => 'VENTA DIRECTA',
                'typemovement' => MovimientosEnum::INGRESO->value,
                'default' => DefaultConceptsEnum::VENTAS->value,
            ]);
            Concept::firstOrCreate([
                'name' => 'PAGO CUOTA',
                'typemovement' => MovimientosEnum::INGRESO->value,
                'default' => DefaultConceptsEnum::PAYCUOTA->value,
            ]);

            $this->call(SeedRoleTableSeeder::class);
        }
    }
}
