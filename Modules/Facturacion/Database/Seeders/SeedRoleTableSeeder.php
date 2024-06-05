<?php

namespace Modules\Facturacion\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $desarrollador = Role::firstOrCreate(['name' => 'DESARROLLADOR']);
        $admin = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $ventas = Role::firstOrCreate(['name' => 'VENDEDOR']);
        $support = Role::firstOrCreate(['name' => 'SOPORTE TÉCNICO']);
        $cotizador = Role::firstOrCreate(['name' => 'COTIZADOR']);

        // FACTURACION
        Permission::firstOrCreate([
            'name' => 'admin.facturacion',
            'descripcion' => 'Administrar comprobantes electrónicos',
            'table' => 'Combrobantes electrónicos',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas, $support, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.delete',
            'descripcion' => 'Eliminar comprobantes electrónicos',
            'table' => 'Combrobantes electrónicos',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.sunat',
            'descripcion' => 'Enviar a SUNAT comprobantes electrónicos',
            'table' => 'Combrobantes electrónicos',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);



        // GUIAS REMISION
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias',
            'descripcion' => 'Administrar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.create',
            'descripcion' => 'Registrar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.delete',
            'descripcion' => 'Eliminar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.sunat',
            'descripcion' => 'Enviar a SUNAT guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);


        // MOTIVOS TRASLADO
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.motivos',
            'descripcion' => 'Administrar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.motivos.create',
            'descripcion' => 'Registrar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.motivos.edit',
            'descripcion' => 'Editar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.facturacion.guias.motivos.delete',
            'descripcion' => 'Eliminar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación',
            'orden' => '5'
        ])->syncRoles([$desarrollador, $admin]);
    }
}
