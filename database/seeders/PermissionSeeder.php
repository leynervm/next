<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // PRODUCTOS
        if (Module::isEnabled('Almacen') || Module::isEnabled('Ventas')) {
            $desarrollador = Role::firstOrCreate(['name' => 'DESARROLLADOR']);
            $admin = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
            $nameModule = Module::isEnabled('Almacen') ? 'Almacén' : 'Ventas';

            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos',
                'descripcion' => 'Administrar productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.create',
                'descripcion' => 'Registrar productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.edit',
                'descripcion' => 'Editar productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.delete',
                'descripcion' => 'Eliminar productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.almacen',
                'descripcion' => 'Editar almacenes de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.especificaciones',
                'descripcion' => 'Editar especificaciones de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.series',
                'descripcion' => 'Administrar series de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.series.edit',
                'descripcion' => 'Editar series de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.garantias',
                'descripcion' => 'Administrar garantías de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.garantias.edit',
                'descripcion' => 'Editar garantías de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
            Permission::updateOrCreate([
                'name' => 'admin.almacen.productos.images',
                'descripcion' => 'Editar imágenes de productos',
                'table' => 'Productos',
                'orden' => '3'
            ], [
                'module' => $nameModule
            ])->syncRoles([$desarrollador, $admin]);
        }
    }
}
