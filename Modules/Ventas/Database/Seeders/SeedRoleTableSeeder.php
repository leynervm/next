<?php

namespace Modules\Ventas\Database\Seeders;

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
        $ventas = Role::firstOrCreate(['name' => 'VENDEDOR']);
        $support = Role::firstOrCreate(['name' => 'SOPORTE TÉCNICO']);
        $cotizador = Role::firstOrCreate(['name' => 'COTIZADOR']);

        // TIPOS DE PAGO VENTAS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.typepayments',
            'descripcion' => 'Administrar tipos de pago',
            'table' => 'Tipos pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.typepayments.edit',
            'descripcion' => 'Editar tipos de pago',
            'table' => 'Tipos pago',
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
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias.create',
            'descripcion' => 'Registrar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias.edit',
            'descripcion' => 'Editar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.categorias.delete',
            'descripcion' => 'Eliminar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);


        // SUBCATEGORIAS
        Permission::firstOrCreate([
            'name' => 'admin.almacen.subcategorias',
            'descripcion' => 'Administrar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
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



        // LISTA PRECIOS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.pricetypes',
            'descripcion' => 'Administrar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
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
        ])->syncRoles([$desarrollador, $admin, $ventas]);
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
        Permission::firstOrCreate([
            'name' => 'admin.administracion.rangos.import',
            'descripcion' => 'Importar lista de rangos',
            'table' => 'Rango precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.rangos.sync',
            'descripcion' => 'Sincronizar precio de venta',
            'table' => 'Rango precios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);


        // VENTAS
        Permission::firstOrCreate([
            'name' => 'admin.ventas',
            'descripcion' => 'Administrar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.create',
            'descripcion' => 'Registrar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.edit',
            'descripcion' => 'Editar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.delete',
            'descripcion' => 'Eliminar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.create.guias',
            'descripcion' => 'Generar y vincular GRE con ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.create.gratuito',
            'descripcion' => 'Registrar ventas gratuitas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.cobranzas',
            'descripcion' => 'Administrar cuentas por cobrar',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.payments',
            'descripcion' => 'Administrar pagos de ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas,]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.payments.edit',
            'descripcion' => 'Registrar pagos de ventas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.ventas.deletes',
            'descripcion' => 'Administrar ventas eliminadas',
            'table' => 'Ventas',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas]);


        // PROMOCIONES
        Permission::firstOrCreate([
            'name' => 'admin.promociones',
            'descripcion' => 'Administrar promociones de productos',
            'table' => 'Promociones',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin, $ventas, $support, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.promociones.create',
            'descripcion' => 'Registrar promociones de productos',
            'table' => 'Promociones',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.promociones.edit',
            'descripcion' => 'Editar promociones de productos',
            'table' => 'Promociones',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.promociones.delete',
            'descripcion' => 'Eliminar promociones de productos',
            'table' => 'Promociones',
            'module' => 'Ventas',
            'orden' => '4'
        ])->syncRoles([$desarrollador, $admin]);
    }
}
