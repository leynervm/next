<?php

namespace Modules\Almacen\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Nwidart\Modules\Facades\Module;
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

        // LISTA PRECIOS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes',
            'descripcion' => 'Administrar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes.create',
            'descripcion' => 'Registrar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes.edit',
            'descripcion' => 'Editar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes.delete',
            'descripcion' => 'Eliminar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes.compras',
            'descripcion' => 'Administrar precios personalizados en compras',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes.productos',
            'descripcion' => 'Administrar precios personalizados en productos',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);

        // RANGOS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.rangos',
            'descripcion' => 'Administrar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.rangos.create',
            'descripcion' => 'Registrar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.rangos.edit',
            'descripcion' => 'Editar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.rangos.delete',
            'descripcion' => 'Eliminar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);


        // CATEGORIAS
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias',
            'descripcion' => 'Administrar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias.create',
            'descripcion' => 'Registrar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias.edit',
            'descripcion' => 'Editar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias.delete',
            'descripcion' => 'Eliminar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);


        // SUBCATEGORIAS
        Permission::firstOrCreate([
            'name' => 'admin.almacen.subcategorias',
            'descripcion' => 'Administrar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.subcategorias.create',
            'descripcion' => 'Registrar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.subcategorias.edit',
            'descripcion' => 'Editar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.subcategorias.delete',
            'descripcion' => 'Eliminar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador,  $admin]);


        // CARACTERISTICAS Y ESPECIFICACIONES
        Permission::firstOrCreate([
            'name' => 'admin.almacen.caracteristicas',
            'descripcion' => 'Administrar características y especificaciones',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.caracteristicas.create',
            'descripcion' => 'Registrar características',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.caracteristicas.edit',
            'descripcion' => 'Editar características',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.caracteristicas.delete',
            'descripcion' => 'Eliminar características',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.especificaciones.create',
            'descripcion' => 'Registrar especificaciones',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.especificaciones.edit',
            'descripcion' => 'Editar especificaciones',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.especificaciones.delete',
            'descripcion' => 'Eliminar especificaciones de productos',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);

        // ALMACEN
        Permission::firstOrCreate([
            'name' => 'admin.almacen',
            'descripcion' => 'Administrar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.create',
            'descripcion' => 'Registrar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.edit',
            'descripcion' => 'Editar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.delete',
            'descripcion' => 'Eliminar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);


        // PROVEEDORTYPES
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.tipos',
            'descripcion' => 'Administrar tipos de proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.tipos.create',
            'descripcion' => 'Registrar tipos proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.tipos.edit',
            'descripcion' => 'Editar tipos proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.tipos.delete',
            'descripcion' => 'Eliminar tipos proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);


        // PROVEEDORS
        Permission::firstOrCreate([
            'name' => 'admin.proveedores',
            'descripcion' => 'Administrar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.create',
            'descripcion' => 'Registrar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.edit',
            'descripcion' => 'Editar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.delete',
            'descripcion' => 'Eliminar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.historial',
            'descripcion' => 'Administrar historial compras del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.pedidos',
            'descripcion' => 'Administrar historial pedidos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.contacts',
            'descripcion' => 'Administrar contactos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.contacts.edit',
            'descripcion' => 'Editar contactos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.phones',
            'descripcion' => 'Administrar teléfonos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.proveedores.phones.edit',
            'descripcion' => 'Editar teléfonos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);














        // COMPRAS
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras',
            'descripcion' => 'Administrar compras',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras.create',
            'descripcion' => 'Registrar compras',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras.close',
            'descripcion' => 'Cerrar compras',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras.delete',
            'descripcion' => 'Eliminar compras',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras.deletes',
            'descripcion' => 'Administrar compras eliminadas',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras.pagos',
            'descripcion' => 'Registrar pagos de compras',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.compras.payments',
            'descripcion' => 'Administrar cuentas por pagar',
            'table' => 'Compras',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);



        // ALMACENAREAS
        Permission::firstOrCreate([
            'name' => 'admin.almacen.almacenareas',
            'descripcion' => 'Administrar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.almacenareas.create',
            'descripcion' => 'Registrar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.almacenareas.edit',
            'descripcion' => 'Editar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.almacenareas.delete',
            'descripcion' => 'Eliminar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);

        // ESTANTESALMACEN
        Permission::firstOrCreate([
            'name' => 'admin.almacen.estantes',
            'descripcion' => 'Administrar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.estantes.create',
            'descripcion' => 'Registrar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.estantes.edit',
            'descripcion' => 'Editar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.estantes.delete',
            'descripcion' => 'Eliminar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);


        // TIPOS DE GARANTIA
        Permission::firstOrCreate([
            'name' => 'admin.almacen.typegarantias',
            'descripcion' => 'Administrar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.typegarantias.create',
            'descripcion' => 'Registrar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.typegarantias.edit',
            'descripcion' => 'Editar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.typegarantias.delete',
            'descripcion' => 'Eliminar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);


        // KARDEX
        Permission::firstOrCreate([
            'name' => 'admin.almacen.kardex',
            'descripcion' => 'Administrar kardex de productos',
            'table' => 'Kardex',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.kardex.series',
            'descripcion' => 'Administrar kardex de series en productos',
            'table' => 'Kardex',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.kardex.series.show',
            'descripcion' => 'Administrar historial de series',
            'table' => 'Kardex',
            'module' => 'Almacén',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
    }
}
