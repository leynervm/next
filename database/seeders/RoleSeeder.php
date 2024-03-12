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
        $desarrollador = Role::create(['name' => 'DESARROLLADOR']);
        $admin = Role::create(['name' => 'ADMINISTRADOR']);

        $desarrollador->users()->attach([User::first()->id]);

        // USERS
        Permission::create([
            'name' => 'admin.users',
            'descripcion' => 'Administrar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.users.create',
            'descripcion' => 'Registrar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.users.edit',
            'descripcion' => 'Editar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.users.delete',
            'descripcion' => 'Eliminar usuarios',
            'table' => 'Usuarios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);



        // ROLES
        Permission::create([
            'name' => 'admin.roles',
            'descripcion' => 'Administrar roles',
            'table' => 'Roles',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.roles.create',
            'descripcion' => 'Registrar roles',
            'table' => 'Roles',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.roles.edit',
            'descripcion' => 'Editar roles',
            'table' => 'Roles',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.roles.delete',
            'descripcion' => 'Eliminar roles',
            'table' => 'Roles',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);



        // PERMISOS
        Permission::create([
            'name' => 'admin.roles.permisos',
            'descripcion' => 'Administrar permisos',
            'table' => 'Permisos',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.roles.permisos.edit',
            'descripcion' => 'Editar permisos',
            'table' => 'Permisos',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);



        // CLIENTES
        Permission::create([
            'name' => 'admin.clientes',
            'descripcion' => 'Adminisrar clientes',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.create',
            'descripcion' => 'Registrar clientes',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.edit',
            'descripcion' => 'Editar clientes',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.delete',
            'descripcion' => 'Eliminar clientes',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.historial',
            'descripcion' => 'Administrar historial ventas cliente',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.contacts',
            'descripcion' => 'Administrar contactos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.contacts.edit',
            'descripcion' => 'Editar contactos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.phones',
            'descripcion' => 'Administrar teléfonos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.clientes.phones.edit',
            'descripcion' => 'Editar teléfonos del cliente',
            'table' => 'Clientes',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);



        // PANEL ADMINISTRATIVO CAJA
        Permission::create([
            'name' => 'admin.cajas',
            'descripcion' => 'Administrar confguración de cajas',
            'table' => 'Administrar cajas',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador,  $admin]);


        // MOVIMIENTOS
        Permission::create([
            'name' => 'admin.cajas.movimientos',
            'descripcion' => 'Administrar movimientos',
            'table' => 'Movimientos caja',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.cajas.movimientos.create',
            'descripcion' => 'Registrar movimientos manuales',
            'table' => 'Movimientos caja',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.cajas.movimientos.delete',
            'descripcion' => 'Eliminar movimientos manuales',
            'table' => 'Movimientos caja',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador,  $admin]);




        // CAJAS MENSUAL
        Permission::create([
            'name' => 'admin.cajas.mensuales',
            'descripcion' => 'Administrar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.mensuales.create',
            'descripcion' => 'Registrar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.mensuales.edit',
            'descripcion' => 'Editar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.mensuales.delete',
            'descripcion' => 'Eliminar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.mensuales.restore',
            'descripcion' => 'Restaurar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.mensuales.close',
            'descripcion' => 'Cerrar cajas mensuales',
            'table' => 'Caja mensual',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);


        // APERTURA CAJAS
        Permission::create([
            'name' => 'admin.cajas.aperturas',
            'descripcion' => 'Administrar apertura cajas',
            'table' => 'Apertura cajas',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.aperturas.create',
            'descripcion' => 'Registrar apertura caja',
            'table' => 'Apertura cajas',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.aperturas.edit',
            'descripcion' => 'Editar apertura caja',
            'table' => 'Apertura cajas',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.aperturas.close',
            'descripcion' => 'Cerrar apertura caja',
            'table' => 'Apertura cajas',
            'module' => 'Caja'
        ])->syncRoles([$desarrollador, $admin]);


        // CONCEPTOS
        Permission::create([
            'name' => 'admin.cajas.conceptos',
            'descripcion' => 'Administrar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.conceptos.create',
            'descripcion' => 'Registrar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.conceptos.edit',
            'descripcion' => 'Editar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.conceptos.delete',
            'descripcion' => 'Eliminar conceptos pago',
            'table' => 'Conceptos pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // FORMAS PAGO
        Permission::create([
            'name' => 'admin.cajas.methodpayments',
            'descripcion' => 'Administrar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.methodpayments.create',
            'descripcion' => 'Registrar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.methodpayments.edit',
            'descripcion' => 'Editar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.cajas.methodpayments.delete',
            'descripcion' => 'Eliminar formas de pago',
            'table' => 'Formas pago',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // ADMINISTRACION EMPRESA
        Permission::create([
            'name' => 'admin.administracion',
            'descripcion' => 'Administrar panel configuración',
            'table' => 'Administración',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.empresa',
            'descripcion' => 'Administrar perfil de empresa',
            'table' => 'Administración',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.empresa.create',
            'descripcion' => 'Registrar perfil de empresa',
            'table' => 'Administración',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.empresa.edit',
            'descripcion' => 'Editar perfil de empresa',
            'table' => 'Administración',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);



        // TYPECOMPROBANTES
        Permission::create([
            'name' => 'admin.administracion.typecomprobantes',
            'descripcion' => 'Administrar series de comprobantes electrónicos',
            'table' => 'Tipos comprobantes electrónicos',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.typecomprobantes.edit',
            'descripcion' => 'Editar series de comprobantes electrónicos',
            'table' => 'Tipos comprobantes electrónicos',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);



        // EMPLOYERS
        Permission::create([
            'name' => 'admin.administracion.employers',
            'descripcion' => 'Administrar personal',
            'table' => 'Personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.create',
            'descripcion' => 'Registrar personal',
            'table' => 'Personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.edit',
            'descripcion' => 'Editar personal',
            'table' => 'Personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.delete',
            'descripcion' => 'Eliminar personal',
            'table' => 'Personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.showdeletes',
            'descripcion' => 'Administrar personal eliminados',
            'table' => 'Personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.restore',
            'descripcion' => 'Restaurar personal eliminados',
            'table' => 'Personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // PAYMENT EMPLOYERS
        Permission::create([
            'name' => 'admin.administracion.employers.payments',
            'descripcion' => 'Administrar pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.payments.create',
            'descripcion' => 'Registrar pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.adelantos.create',
            'descripcion' => 'Registrar adelantos pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.employers.payments.delete',
            'descripcion' => 'Eliminar pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // SUCURSALES
        Permission::create([
            'name' => 'admin.administracion.sucursales',
            'descripcion' => 'Administrar sucursales y/o locales',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.create',
            'descripcion' => 'Registrar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.edit',
            'descripcion' => 'Editar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.delete',
            'descripcion' => 'Eliminar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.restore',
            'descripcion' => 'Restaurar sucursal y/o local',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.almacenes',
            'descripcion' => 'Visualizar almacenes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.almacenes.edit',
            'descripcion' => 'Administrar almacenes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.seriecomprobantes',
            'descripcion' => 'Visualizar series de comprobantes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.seriecomprobantes.edit',
            'descripcion' => 'Administrar series de comprobantes de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.boxes',
            'descripcion' => 'Visualizar cajas de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.sucursales.boxes.edit',
            'descripcion' => 'Administrar cajas de sucursal',
            'table' => 'Sucursales y/o locales',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);




        // MARCAS
        Permission::create([
            'name' => 'admin.almacen.marcas',
            'descripcion' => 'Administrar marcas productos',
            'table' => 'Marcas',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.marcas.create',
            'descripcion' => 'Registrar marcas productos',
            'table' => 'Marcas',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.marcas.edit',
            'descripcion' => 'Editar marcas productos',
            'table' => 'Marcas',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.marcas.delete',
            'descripcion' => 'Eliminar marcas productos',
            'table' => 'Marcas',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // CATEGORIAS
        Permission::create([
            'name' => 'admin.almacen.categorias',
            'descripcion' => 'Administrar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.almacen.categorias.create',
            'descripcion' => 'Registrar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.almacen.categorias.edit',
            'descripcion' => 'Editar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.almacen.categorias.delete',
            'descripcion' => 'Eliminar categorías de productos',
            'table' => 'Categorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);


        // SUBCATEGORIAS
        Permission::create([
            'name' => 'admin.almacen.subcategorias',
            'descripcion' => 'Administrar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.almacen.subcategorias.create',
            'descripcion' => 'Registrar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.almacen.subcategorias.edit',
            'descripcion' => 'Editar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);
        Permission::create([
            'name' => 'admin.almacen.subcategorias.delete',
            'descripcion' => 'Eliminar subcategorías de productos',
            'table' => 'Subcategorías',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador,  $admin]);


        // CARACTERISTICAS Y ESPECIFICACIONES
        Permission::create([
            'name' => 'admin.almacen.caracteristicas',
            'descripcion' => 'Administrar características y especificaciones',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.caracteristicas.create',
            'descripcion' => 'Registrar características',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.caracteristicas.edit',
            'descripcion' => 'Editar características',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.caracteristicas.delete',
            'descripcion' => 'Eliminar características',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.especificaciones.create',
            'descripcion' => 'Registrar especificaciones',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.especificaciones.edit',
            'descripcion' => 'Editar especificaciones',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.especificaciones.delete',
            'descripcion' => 'Eliminar especificaciones de productos',
            'table' => 'Características y especificaciones',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // UNIDADES MEDIDA
        Permission::create([
            'name' => 'admin.administracion.units',
            'descripcion' => 'Administrar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.units.create',
            'descripcion' => 'Registrar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.units.edit',
            'descripcion' => 'Editar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.units.delete',
            'descripcion' => 'Eliminar unidades de medida',
            'table' => 'Unidades medida',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // LISTA PRECIOS
        Permission::create([
            'name' => 'admin.administracion.pricetypes',
            'descripcion' => 'Administrar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.pricetypes.create',
            'descripcion' => 'Registrar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.pricetypes.edit',
            'descripcion' => 'Editar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.pricetypes.delete',
            'descripcion' => 'Eliminar lista de precios',
            'table' => 'Lista precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.pricetypes.compras',
            'descripcion' => 'Administrar precios personalizados en compras',
            'table' => 'Lista precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.pricetypes.productos',
            'descripcion' => 'Administrar precios personalizados en productos',
            'table' => 'Lista precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // RANGOS
        Permission::create([
            'name' => 'admin.administracion.rangos',
            'descripcion' => 'Administrar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.rangos.create',
            'descripcion' => 'Registrar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.rangos.edit',
            'descripcion' => 'Editar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.administracion.rangos.delete',
            'descripcion' => 'Eliminar rango de precios',
            'table' => 'Rango precios',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);


        // AREAS DE TRABAJO
        Permission::create([
            'name' => 'admin.areas',
            'descripcion' => 'Administrar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.areas.create',
            'descripcion' => 'Registrar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.areas.edit',
            'descripcion' => 'Editar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.areas.delete',
            'descripcion' => 'Eliminar areas de trabajo',
            'table' => 'Areas trabajo',
            'module' => 'Principal'
        ])->syncRoles([$desarrollador, $admin]);




        // ALMACEN
        Permission::create([
            'name' => 'admin.almacen',
            'descripcion' => 'Administrar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.create',
            'descripcion' => 'Registrar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.edit',
            'descripcion' => 'Editar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.delete',
            'descripcion' => 'Eliminar almacenes',
            'table' => 'Almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // PROVEEDORTYPES
        Permission::create([
            'name' => 'admin.proveedores.tipos',
            'descripcion' => 'Administrar tipos de proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.tipos.create',
            'descripcion' => 'Registrar tipos proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.tipos.edit',
            'descripcion' => 'Editar tipos proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.tipos.delete',
            'descripcion' => 'Eliminar tipos proveedor',
            'table' => 'Tipos Proveedor',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // PROVEEDORS
        Permission::create([
            'name' => 'admin.proveedores',
            'descripcion' => 'Administrar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.create',
            'descripcion' => 'Registrar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.edit',
            'descripcion' => 'Editar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.delete',
            'descripcion' => 'Eliminar proveedores',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.historial',
            'descripcion' => 'Administrar historial compras del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.pedidos',
            'descripcion' => 'Administrar historial pedidos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.contacts',
            'descripcion' => 'Administrar contactos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.contacts.edit',
            'descripcion' => 'Editar contactos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.phones',
            'descripcion' => 'Administrar teléfonos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.proveedores.phones.edit',
            'descripcion' => 'Editar teléfonos del proveedor',
            'table' => 'Proveedores',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);



        // PRODUCTOS
        Permission::create([
            'name' => 'admin.almacen.productos',
            'descripcion' => 'Administrar productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.create',
            'descripcion' => 'Registrar productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.edit',
            'descripcion' => 'Editar productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.delete',
            'descripcion' => 'Eliminar productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.almacen',
            'descripcion' => 'Editar almacenes de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.especificaciones',
            'descripcion' => 'Editar especificaciones de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.series',
            'descripcion' => 'Administrar series de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.series.edit',
            'descripcion' => 'Editar series de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.garantias',
            'descripcion' => 'Administrar garantías de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.garantias.edit',
            'descripcion' => 'Editar garantías de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.productos.images',
            'descripcion' => 'Editar imágenes de productos',
            'table' => 'Productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // COMPRAS
        Permission::create([
            'name' => 'admin.almacen.compras',
            'descripcion' => 'Administrar compras',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.compras.create',
            'descripcion' => 'Registrar compras',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.compras.close',
            'descripcion' => 'Cerrar compras',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.compras.delete',
            'descripcion' => 'Eliminar compras',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.compras.deletes',
            'descripcion' => 'Administrar compras eliminadas',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.compras.pagos',
            'descripcion' => 'Registrar pagos de compras',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.compras.payments',
            'descripcion' => 'Administrar cuentas por pagar',
            'table' => 'Compras',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);



        // ALMACENAREAS
        Permission::create([
            'name' => 'admin.almacen.almacenareas',
            'descripcion' => 'Administrar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.almacenareas.create',
            'descripcion' => 'Registrar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.almacenareas.edit',
            'descripcion' => 'Editar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.almacenareas.delete',
            'descripcion' => 'Eliminar áreas de almacén',
            'table' => 'Areas almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);

        // ESTANTESALMACEN
        Permission::create([
            'name' => 'admin.almacen.estantes',
            'descripcion' => 'Administrar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.estantes.create',
            'descripcion' => 'Registrar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.estantes.edit',
            'descripcion' => 'Editar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.estantes.delete',
            'descripcion' => 'Eliminar estantes de almacén',
            'table' => 'Estantes almacén',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // TIPOS DE GARANTIA
        Permission::create([
            'name' => 'admin.almacen.typegarantias',
            'descripcion' => 'Administrar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.typegarantias.create',
            'descripcion' => 'Registrar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.typegarantias.edit',
            'descripcion' => 'Editar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.typegarantias.delete',
            'descripcion' => 'Eliminar tipos de garantía de productos',
            'table' => 'Tipos garantía productos',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);


        // KARDEX
        Permission::create([
            'name' => 'admin.almacen.kardex',
            'descripcion' => 'Administrar kardex de productos',
            'table' => 'Kardex',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.kardex.series',
            'descripcion' => 'Administrar kardex de series en productos',
            'table' => 'Kardex',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.almacen.kardex.series.show',
            'descripcion' => 'Administrar historial de series',
            'table' => 'Kardex',
            'module' => 'Almacén'
        ])->syncRoles([$desarrollador, $admin]);



        // VENTAS
        Permission::create([
            'name' => 'admin.ventas',
            'descripcion' => 'Administrar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.create',
            'descripcion' => 'Registrar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.edit',
            'descripcion' => 'Editar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.delete',
            'descripcion' => 'Eliminar ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.create.igv',
            'descripcion' => 'Registrar ventas con IGV',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.create.guias',
            'descripcion' => 'Generar y vincular GRE con ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.create.gratuito',
            'descripcion' => 'Registrar ventas gratuitas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.cobranzas',
            'descripcion' => 'Administrar cuentas por cobrar',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.payments',
            'descripcion' => 'Administrar pagos de ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.payments.edit',
            'descripcion' => 'Registrar pagos de ventas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.ventas.deletes',
            'descripcion' => 'Administrar ventas eliminadas',
            'table' => 'Ventas',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador, $admin]);


        // PROMOCIONES
        Permission::create([
            'name' => 'admin.promociones',
            'descripcion' => 'Administrar promociones de productos',
            'table' => 'Promociones',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador]);
        Permission::create([
            'name' => 'admin.promociones.create',
            'descripcion' => 'Registrar promociones de productos',
            'table' => 'Promociones',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador]);
        Permission::create([
            'name' => 'admin.promociones.ofertas',
            'descripcion' => 'Administrar ofertas de productos',
            'table' => 'Promociones',
            'module' => 'Ventas'
        ])->syncRoles([$desarrollador]);



        // FACTURACION
        Permission::create([
            'name' => 'admin.facturacion',
            'descripcion' => 'Administrar comprobantes electrónicos',
            'table' => 'Facturación electrónica',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.edit',
            'descripcion' => 'Editar comprobantes electrónicos',
            'table' => 'Facturación electrónica',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.delete',
            'descripcion' => 'Eliminar comprobantes electrónicos',
            'table' => 'Facturación electrónica',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.sunat',
            'descripcion' => 'Enviar a SUNAT comprobantes electrónicos',
            'table' => 'Facturación electrónica',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);



        // GUIAS REMISION
        Permission::create([
            'name' => 'admin.facturacion.guias',
            'descripcion' => 'Administrar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.create',
            'descripcion' => 'Registrar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.edit',
            'descripcion' => 'Editar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.delete',
            'descripcion' => 'Eliminar guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.sunat',
            'descripcion' => 'Enviar a SUNAT guías de remisión',
            'table' => 'Guías remisión',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);


        // MOTIVOS TRASLADO
        Permission::create([
            'name' => 'admin.facturacion.guias.motivos',
            'descripcion' => 'Administrar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.motivos.create',
            'descripcion' => 'Registrar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.motivos.edit',
            'descripcion' => 'Editar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::create([
            'name' => 'admin.facturacion.guias.motivos.delete',
            'descripcion' => 'Eliminar motivos de traslado interno',
            'table' => 'Motivos traslado',
            'module' => 'Facturación'
        ])->syncRoles([$desarrollador, $admin]);

        // Permission::create([
        //     'name' => '',
        //     'descripcion' => '',
        //     'table' => ''
        // ])->syncRoles([$desarrollador]);
    }
}
