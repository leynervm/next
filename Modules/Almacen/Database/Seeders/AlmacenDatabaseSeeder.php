<?php

namespace Modules\Almacen\Database\Seeders;

use App\Enums\DefaultConceptsEnum;
use App\Enums\MovimientosEnum;
use App\Models\Concept;
use Database\Seeders\AlmacenSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;

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
        $this->call(AlmacenSeeder::class);
        $this->call(PermissionSeeder::class);

        if (Module::isEnabled('Almacen')) {
            Concept::firstOrCreate([
                'name' => 'PAGO COMPRA',
                'typemovement' => MovimientosEnum::EGRESO->value,
                'default' => DefaultConceptsEnum::COMPRA->value,
            ]);

            Concept::firstOrCreate([
                'name' => 'PAGO CUOTA COMPRA',
                'typemovement' => MovimientosEnum::EGRESO->value,
                'default' => DefaultConceptsEnum::PAYCUOTACOMPRA->value,
            ]);

            $this->call(SeedRoleTableSeeder::class);
            $this->call(SeedEstantesTableSeeder::class);
        }
    }
}
