<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Role;

class PermissionCotizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // COTIZACIONES
        if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas')) {
            $desarrollador = Role::firstOrCreate(['name' => 'DESARROLLADOR']);
            $admin = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);

            Permission::updateOrCreate([
                'name' => 'admin.cotizaciones',
                'orden' => '8',
            ], [
                'descripcion' => 'Administrar cotizaciones',
                'table' => 'Cotizaciones',
                'module' => 'Cotizaciones'
            ])->syncRoles([$desarrollador, $admin]);

            Permission::updateOrCreate([
                'name' => 'admin.cotizaciones.create',
                'orden' => '8',
            ], [
                'descripcion' => 'Registrar cotizaciones',
                'table' => 'Cotizaciones',
                'module' => 'Cotizaciones'
            ])->syncRoles([$desarrollador, $admin]);

            Permission::updateOrCreate([
                'name' => 'admin.cotizaciones.edit',
                'orden' => '8',
            ], [
                'descripcion' => 'Editar cotización',
                'table' => 'Cotizaciones',
                'module' => 'Cotizaciones'
            ])->syncRoles([$desarrollador, $admin]);

            Permission::updateOrCreate([
                'name' => 'admin.cotizaciones.aprobar',
                'orden' => '8',
            ], [
                'descripcion' => 'Aprobar y desaprobar cotización',
                'table' => 'Cotizaciones',
                'module' => 'Cotizaciones'
            ])->syncRoles([$desarrollador, $admin]);

            Permission::updateOrCreate([
                'name' => 'admin.cotizaciones.facturar',
                'orden' => '8',
            ], [
                'descripcion' => 'Generar venta de cotización',
                'table' => 'Cotizaciones',
                'module' => 'Cotizaciones'
            ])->syncRoles([$desarrollador, $admin]);

            Permission::updateOrCreate([
                'name' => 'admin.cotizaciones.delete',
                'orden' => '8',
            ], [
                'descripcion' => 'Eliminar cotización',
                'table' => 'Cotizaciones',
                'module' => 'Cotizaciones'
            ])->syncRoles([$desarrollador, $admin]);
        }
    }
}
