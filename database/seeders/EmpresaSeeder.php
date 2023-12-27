<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = Empresa::create([
            'document' => '20538954099',
            'name' => 'NEXT TECHNOLOGIES E.I.R.L',
            'estado' => 'ACTIVO',
            'condicion' => 'HABIDO',
            'direccion' => 'Cal. Zarumilla Nro. 1375 Sector Pueblo Nuevo',
            'email' => null,
            'web' => 'next.net.pe',
            'icono' => null,
            'privatekey' => null,
            'publickey' => null,
            'usuariosol' => null,
            'clavesol' => null,
            'montoadelanto' => 300,
            'uselistprice' => 1,
            'usepricedolar' => 0,
            'viewpricedolar' => 0,
            'tipocambio' => null,
            'tipocambioauto' => 0,
            'igv' => 18,
            'default' => 1,
            'ubigeo_id' => 636,
        ]);

        $sucursal = $empresa->sucursals()->create([
            'name' => 'NEXT TECHNOLOGIES E.I.R.L',
            'direccion' => 'Cal. Zarumilla Nro. 1375 Sector Pueblo Nuevo',
            'codeanexo' => '0000',
            'default' => 1,
            'ubigeo_id' => 636,
        ]);

        $sucursalBagua = $empresa->sucursals()->create([
            'name' => 'SUCURSAL BAGUA',
            'direccion' => 'CALLE LOS PINOS CUADRA 8',
            'codeanexo' => '0002',
            'ubigeo_id' => 22,
        ]);

        $sucursalTrujillo = $empresa->sucursals()->create([
            'name' => 'SUCURSAL TRUJILLO',
            'direccion' => 'AV. PRINCIPAL CARRETERA PANAMERICANA NORTE KM11',
            'codeanexo' => '0001',
            'ubigeo_id' => 1160,
        ]);

        $sucursalSanignacio = $empresa->sucursals()->create([
            'name' => 'SUCURSAL SAN IGNACIO',
            'direccion' => 'AV MESONES MURO CUADRA 6 MANZ. 12',
            'codeanexo' => '0003',
            'ubigeo_id' => 648,
        ]);

        $sucursal->cajas()->createMany([
            [
                'name' => 'CAJA J01',
            ],
            [
                'name' => 'CAJA J02',
            ]
        ]);

        $sucursalBagua->cajas()->createMany([
            [
                'name' => 'CAJA BH01',
            ],
            [
                'name' => 'CAJA BH02',
            ]
        ]);

        $sucursalTrujillo->cajas()->createMany([
            [
                'name' => 'CAJA T01',
            ],
            [
                'name' => 'CAJA T02',
            ]
        ]);

        $sucursalSanignacio->cajas()->createMany([
            [
                'name' => 'CAJA SI01',
            ]
        ]);

        // if (Module::isEnabled('Almacen')) {
        $sucursal->almacens()->createMany([
            [
                'name' => 'ALMACEN J01',
                'default' => 1,
            ],
            [
                'name' => 'ALMACEN J02',
                'default' => 0,
            ]
        ]);

        $sucursalBagua->almacens()->createMany([
            [
                'name' => 'ALMACEN BH01',
                'default' => 1,
            ],
            [
                'name' => 'ALMACEN BH02',
                'default' => 0,
            ]
        ]);

        $sucursalTrujillo->almacens()->createMany([
            [
                'name' => 'ALMACEN T01',
                'default' => 0,
            ],
        ]);

        $sucursalSanignacio->almacens()->createMany([
            [
                'name' => 'ALMACEN SI01',
                'default' => 0,
            ],
        ]);
        // }

        $sucursal->seriecomprobantes()->sync([1 => ['default' => 1], 2]);
        $sucursalBagua->seriecomprobantes()->attach(1, ['default' => 1]);
        $sucursalSanignacio->seriecomprobantes()->attach(1, ['default' => 1]);
    }
}
