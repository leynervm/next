<?php

namespace Database\Seeders;

use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Sucursal;
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
            'document' => '20601581125',
            'name' => 'NEXT TECHNOLOGY S.A.C.',
            'estado' => 'ACTIVO',
            'condicion' => 'HABIDO',
            'direccion' => 'AV. PASEO DE LA REPUBLICA NRO 4644 DEP. 404',
            'email' => 'next@next.net.pe',
            'web' => 'www.next.net.pe',
            'icono' => null,
            'cert' => null,
            'passwordcert' => null,
            'usuariosol' => null,
            'clavesol' => null,
            'clientid' => null,
            'clientsecret' => null,
            'montoadelanto' => 300,
            'uselistprice' => 0,
            'usepricedolar' => 0,
            'viewpricedolar' => 0,
            'tipocambio' => null,
            'tipocambioauto' => 0,
            'limitsucursals' => Sucursal::LIMITE,
            'igv' => 18,
            'sendmode' => 0,
            'default' => 1,
            'ubigeo_id' => 636,
        ]);

        $sucursalprincipal = $empresa->sucursals()->create([
            'name' => 'NEXT TECHNOLOGY S.A.C.',
            'direccion' => 'AV. PASEO DE LA REPUBLICA NRO 4644 DEP. 404',
            'codeanexo' => '0000',
            'default' => Sucursal::DEFAULT,
            'ubigeo_id' => 1302,
        ]);

        $trujillo = $empresa->sucursals()->create([
            'name' => 'NEXT TECHNOLOGY S.A.C. - TRUJILLO',
            'direccion' => 'CALLE LOS LAURELES CUDRA 936',
            'codeanexo' => '0002',
            'ubigeo_id' => 1164,
        ]);

        $sanignacio = $empresa->sucursals()->create([
            'name' => 'NEXT TECHNOLOGY S.A.C. - SAN IGNACIO',
            'direccion' => 'CALLE 28 DE JULIO CUADRA 1620',
            'codeanexo' => '0001',
            'ubigeo_id' => 648,
        ]);

        $bagua = $empresa->sucursals()->create([
            'name' => 'NEXT TECHNOLOGY S.A.C. - BAGUA',
            'direccion' => 'CALLE LOS AROMOS URBANIZACION LAS FLORES CUADRA 270',
            'codeanexo' => '0003',
            'ubigeo_id' => 22,
        ]);

        $huabal = $empresa->sucursals()->create([
            'name' => 'NEXT TECHNOLOGY S.A.C. - HUABAL',
            'direccion' => 'CALLE 28 DE JULIO CUADRA 730',
            'codeanexo' => '0004',
            'ubigeo_id' => 640,
        ]);

        $sucursalprincipal->boxes()->createMany([
            [
                'name' => 'CAJA J01',
                'apertura' => 100,
            ],
            [
                'name' => 'CAJA J02',
                'apertura' => 100,
            ]
        ]);

        $bagua->boxes()->createMany([
            [
                'name' => 'CAJA BG01',
                'apertura' => 100,
            ],
            [
                'name' => 'CAJA BG02',
                'apertura' => 100,
            ]
        ]);

        $trujillo->boxes()->createMany([
            [
                'name' => 'CAJA T01',
                'apertura' => 50,
            ],
            [
                'name' => 'CAJA T02',
                'apertura' => 50,
            ]
        ]);

        $sanignacio->boxes()->createMany([
            [
                'name' => 'CAJA SI01',
                'apertura' => 50,
            ]
        ]);

        if (Module::isDisabled('Almacen') && Module::isEnabled('Ventas')) {
            $sucursalprincipal->almacens()->attach(Almacen::first()->id);
        }
    }
}
