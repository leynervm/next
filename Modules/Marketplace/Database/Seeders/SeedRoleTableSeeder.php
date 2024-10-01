<?php

namespace Modules\Marketplace\Database\Seeders;

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


        Permission::firstOrCreate([
            'name' => 'admin.marketplace.orders',
            'descripcion' => 'Administrar pedidos web',
            'table' => 'Pedidos web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.orders.confirmpay',
            'descripcion' => 'Confirmar pago de pedidos',
            'table' => 'Pedidos web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.orders.discountstock',
            'descripcion' => 'Desconcontar stock de pedidos',
            'table' => 'Pedidos web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.orders.deletestock',
            'descripcion' => 'Eliminar stock de pedidos',
            'table' => 'Pedidos web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.trackings.create',
            'descripcion' => 'Registrar seguimientos de pedidos',
            'table' => 'Pedidos web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.trackings.delete',
            'descripcion' => 'Eliminar seguimientos de pedidos',
            'table' => 'Pedidos web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);


        Permission::firstOrCreate([
            'name' => 'admin.marketplace.transacciones',
            'descripcion' => 'Administrar transacciones web',
            'table' => 'Transacciones web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        // Permission::firstOrCreate([
        //     'name' => 'admin.marketplace.transacciones.create',
        //     'descripcion' => 'Registrar transacciones web',
        //     'table' => 'Transacciones web',
        //     'module' => 'Tienda Virtual',
        //     'orden' => '7'
        // ])->syncRoles([$desarrollador, $admin]);
        // Permission::firstOrCreate([
        //     'name' => 'admin.marketplace.transacciones.edit',
        //     'descripcion' => 'Editar transacciones web',
        //     'table' => 'Transacciones web',
        //     'module' => 'Tienda Virtual',
        //     'orden' => '7'
        // ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.transacciones.delete',
            'descripcion' => 'Eliminar transacciones web',
            'table' => 'Transacciones web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);


        Permission::firstOrCreate([
            'name' => 'admin.marketplace.userweb',
            'descripcion' => 'Administrar usuarios web',
            'table' => 'Usuarios web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.userweb.create',
            'descripcion' => 'Registrar usuarios web',
            'table' => 'Usuarios web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.userweb.edit',
            'descripcion' => 'Editar usuarios web',
            'table' => 'Usuarios web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.userweb.delete',
            'descripcion' => 'Eliminar usuarios web',
            'table' => 'Usuarios web',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);


        Permission::firstOrCreate([
            'name' => 'admin.marketplace.trackingstates',
            'descripcion' => 'Administrar nombres de seguimiento de tracking',
            'table' => 'Estados tracking',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.trackingstates.create',
            'descripcion' => 'Registrar nombres de seguimiento de tracking',
            'table' => 'Estados tracking',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.trackingstates.edit',
            'descripcion' => 'Editar nombres de seguimiento de tracking',
            'table' => 'Estados tracking',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.trackingstates.delete',
            'descripcion' => 'Eliminar nombres de seguimiento de tracking',
            'table' => 'Estados tracking',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);


        Permission::firstOrCreate([
            'name' => 'admin.marketplace.shipmenttypes',
            'descripcion' => 'Administrar tipos de envío',
            'table' => 'Tipos de envío',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.shipmenttypes.edit',
            'descripcion' => 'Editar tipos de envío',
            'table' => 'Tipos de envío',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);



        Permission::firstOrCreate([
            'name' => 'admin.marketplace.sliders',
            'descripcion' => 'Administrar sliders web',
            'table' => 'Sliders',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.sliders.create',
            'descripcion' => 'Registrar sliders web',
            'table' => 'Sliders',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.sliders.edit',
            'descripcion' => 'Editar sliders web',
            'table' => 'Sliders',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.sliders.pause',
            'descripcion' => 'Pausar sliders web',
            'table' => 'Sliders',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.sliders.order',
            'descripcion' => 'Cambiar orden sliders web',
            'table' => 'Sliders',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.sliders.delete',
            'descripcion' => 'Eliminar sliders web',
            'table' => 'Sliders',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);


        Permission::firstOrCreate([
            'name' => 'admin.marketplace.claimbooks',
            'descripcion' => 'Administrar libros de reclamaciones',
            'table' => 'Libros reclamación',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.marketplace.claimbooks.show',
            'descripcion' => 'Tramitar libros de reclamaciones',
            'table' => 'Libros reclamación',
            'module' => 'Tienda Virtual',
            'orden' => '7'
        ])->syncRoles([$desarrollador, $admin]);
    }
}
