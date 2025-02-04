<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $desarrollador = Role::firstOrCreate(['name' => 'DESARROLLADOR']);
        $admin = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $desarrollador->users()->sync([User::first()->id]);
        $ventas = Role::firstOrCreate(['name' => 'VENDEDOR']);
        $support = Role::firstOrCreate(['name' => 'SOPORTE TÉCNICO']);
        $cotizador = Role::firstOrCreate(['name' => 'COTIZADOR']);

        // USERS
        Permission::firstOrCreate([
            'name' => 'admin.users',
            'descripcion' => 'Administrar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.users.create',
            'descripcion' => 'Registrar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.users.edit',
            'descripcion' => 'Editar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.users.delete',
            'descripcion' => 'Eliminar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.users.restore',
            'descripcion' => 'Restaurar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);



        // ROLES
        Permission::firstOrCreate([
            'name' => 'admin.roles',
            'descripcion' => 'Administrar roles',
            'table' => 'Roles',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.roles.create',
            'descripcion' => 'Registrar roles',
            'table' => 'Roles',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.roles.edit',
            'descripcion' => 'Editar roles',
            'table' => 'Roles',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.roles.delete',
            'descripcion' => 'Eliminar roles',
            'table' => 'Roles',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);



        // PERMISOS
        Permission::firstOrCreate([
            'name' => 'admin.roles.permisos',
            'descripcion' => 'Administrar permisos',
            'table' => 'Permisos',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.roles.permisos.edit',
            'descripcion' => 'Editar permisos',
            'table' => 'Permisos',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);



        // CLIENTES
        Permission::firstOrCreate([
            'name' => 'admin.clientes',
            'descripcion' => 'Administrar clientes',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $support, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.create',
            'descripcion' => 'Registrar clientes',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $support, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.edit',
            'descripcion' => 'Editar clientes',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.delete',
            'descripcion' => 'Eliminar clientes',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.historial',
            'descripcion' => 'Administrar historial ventas cliente',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.contacts',
            'descripcion' => 'Administrar contactos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.contacts.edit',
            'descripcion' => 'Editar contactos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.phones',
            'descripcion' => 'Administrar teléfonos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.clientes.phones.edit',
            'descripcion' => 'Editar teléfonos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas, $cotizador]);



        // MOVIMIENTOS
        Permission::firstOrCreate([
            'name' => 'admin.cajas.movimientos',
            'descripcion' => 'Administrar movimientos',
            'table' => 'Movimientos caja',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador,  $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.movimientos.create',
            'descripcion' => 'Registrar movimientos manuales',
            'table' => 'Movimientos caja',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador,  $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.movimientos.delete',
            'descripcion' => 'Eliminar movimientos manuales',
            'table' => 'Movimientos caja',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador,  $admin, $ventas]);




        // CAJAS MENSUAL
        Permission::firstOrCreate([
            'name' => 'admin.cajas.mensuales',
            'descripcion' => 'Administrar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.mensuales.create',
            'descripcion' => 'Registrar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.mensuales.edit',
            'descripcion' => 'Editar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.mensuales.delete',
            'descripcion' => 'Eliminar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.mensuales.restore',
            'descripcion' => 'Restaurar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.mensuales.close',
            'descripcion' => 'Activar y cerrar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);



        // APERTURA CAJAS
        Permission::firstOrCreate([
            'name' => 'admin.cajas.aperturas',
            'descripcion' => 'Administrar apertura cajas',
            'table' => 'Apertura cajas',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.aperturas.create',
            'descripcion' => 'Registrar apertura caja',
            'table' => 'Apertura cajas',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.aperturas.edit',
            'descripcion' => 'Editar apertura caja',
            'table' => 'Apertura cajas',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.aperturas.close',
            'descripcion' => 'Cerrar apertura caja',
            'table' => 'Apertura cajas',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.aperturas.closeothers',
            'descripcion' => 'Cerrar apertura caja de otros usuarios',
            'table' => 'Apertura cajas',
            'module' => 'Caja',
            'orden' => '2'
        ])->syncRoles([$desarrollador, $admin]);


        // CONCEPTOS
        Permission::firstOrCreate([
            'name' => 'admin.cajas.conceptos',
            'descripcion' => 'Administrar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.conceptos.create',
            'descripcion' => 'Registrar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.conceptos.edit',
            'descripcion' => 'Editar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.conceptos.delete',
            'descripcion' => 'Eliminar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);


        // FORMAS PAGO
        Permission::firstOrCreate([
            'name' => 'admin.cajas.methodpayments',
            'descripcion' => 'Administrar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.methodpayments.create',
            'descripcion' => 'Registrar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.methodpayments.edit',
            'descripcion' => 'Editar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin, $ventas]);
        Permission::firstOrCreate([
            'name' => 'admin.cajas.methodpayments.delete',
            'descripcion' => 'Eliminar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);


        // ADMINISTRACION EMPRESA
        Permission::firstOrCreate([
            'name' => 'admin.administracion',
            'descripcion' => 'Administrar panel configuración',
            'table' => 'Administración',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.empresa',
            'descripcion' => 'Administrar perfil de empresa',
            'table' => 'Administración',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.empresa.create',
            'descripcion' => 'Registrar perfil de empresa',
            'table' => 'Administración',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.empresa.edit',
            'descripcion' => 'Editar perfil de empresa',
            'table' => 'Administración',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);



        // TYPECOMPROBANTES
        Permission::firstOrCreate([
            'name' => 'admin.administracion.typecomprobantes',
            'descripcion' => 'Administrar series de comprobantes',
            'table' => 'Tipos comprobantes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.typecomprobantes.edit',
            'descripcion' => 'Editar series de comprobantes',
            'table' => 'Tipos comprobantes',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);



        // SUCURSALES
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales',
            'descripcion' => 'Administrar sucursales y/o locales',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.create',
            'descripcion' => 'Registrar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.edit',
            'descripcion' => 'Editar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.delete',
            'descripcion' => 'Eliminar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.restore',
            'descripcion' => 'Restaurar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.almacenes',
            'descripcion' => 'Visualizar almacenes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.almacenes.edit',
            'descripcion' => 'Administrar almacenes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.seriecomprobantes',
            'descripcion' => 'Visualizar series de comprobantes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.seriecomprobantes.edit',
            'descripcion' => 'Administrar series de comprobantes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.boxes',
            'descripcion' => 'Visualizar cajas de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.sucursales.boxes.edit',
            'descripcion' => 'Administrar cajas de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);

        // MARCAS
        Permission::firstOrCreate([
            'name' => 'admin.almacen.marcas',
            'descripcion' => 'Administrar marcas productos',
            'table' => 'Marcas',
            'module' => 'Principal',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin, $ventas, $support, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.marcas.create',
            'descripcion' => 'Registrar marcas productos',
            'table' => 'Marcas',
            'module' => 'Principal',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin, $ventas, $support, $cotizador]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.marcas.edit',
            'descripcion' => 'Editar marcas productos',
            'table' => 'Marcas',
            'module' => 'Principal',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.almacen.marcas.delete',
            'descripcion' => 'Eliminar marcas productos',
            'table' => 'Marcas',
            'module' => 'Principal',
            'orden' => '3'
        ])->syncRoles([$desarrollador, $admin]);

        // UNIDADES MEDIDA
        Permission::firstOrCreate([
            'name' => 'admin.administracion.units',
            'descripcion' => 'Administrar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.units.create',
            'descripcion' => 'Registrar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.units.edit',
            'descripcion' => 'Editar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.units.delete',
            'descripcion' => 'Eliminar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);



        // AREAS DE TRABAJO
        Permission::firstOrCreate([
            'name' => 'admin.administracion.areaswork',
            'descripcion' => 'Administrar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.areaswork.create',
            'descripcion' => 'Registrar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.areaswork.edit',
            'descripcion' => 'Editar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.areaswork.delete',
            'descripcion' => 'Eliminar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal',
            'orden' => '1'
        ])->syncRoles([$desarrollador, $admin]);


        // REPORTES
        Permission::firstOrCreate([
            'name' => 'admin.reportes',
            'descripcion' => 'Generar reportes',
            'table' => 'Reportes',
            'module' => 'Principal',
            'orden' => '99'
        ])->syncRoles([$desarrollador, $admin]);

        // Permission::firstOrCreate([
        //     'name' => '',
        //     'descripcion' => '',
        //     'table' => ''
        // ])->syncRoles([$desarrollador]);


        Permission::firstOrCreate([
            'name' => 'admin.reportes.cajas.allsucursals',
            'descripcion' => 'Mostrar todas las sucursales',
            'table' => 'Reportes',
            'module' => 'Principal',
            'orden' => '99'
        ])->syncRoles([$desarrollador, $admin]);

        

    }
}
