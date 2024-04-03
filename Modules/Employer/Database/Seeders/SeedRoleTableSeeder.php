<?php

namespace Modules\Employer\Database\Seeders;

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

        // EMPLOYERS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers',
            'descripcion' => 'Administrar personal',
            'table' => 'Personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.create',
            'descripcion' => 'Registrar personal',
            'table' => 'Personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.edit',
            'descripcion' => 'Editar personal',
            'table' => 'Personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.delete',
            'descripcion' => 'Eliminar personal',
            'table' => 'Personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.showdeletes',
            'descripcion' => 'Administrar personal eliminados',
            'table' => 'Personal',
            'module' => 'Principal',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.restore',
            'descripcion' => 'Restaurar personal eliminados',
            'table' => 'Personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);


        // TURNOS HORARIOS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.turnos',
            'descripcion' => 'Administrar turnos laborales',
            'table' => 'Turnos',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.turnos.create',
            'descripcion' => 'Registrar turno laboral',
            'table' => 'Turnos',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.turnos.edit',
            'descripcion' => 'Editar turno laboral',
            'table' => 'Turnos',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.turnos.delete',
            'descripcion' => 'Eliminar turno laboral',
            'table' => 'Turnos',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);



        // PAYMENT EMPLOYERS
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.payments',
            'descripcion' => 'Administrar pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.payments.create',
            'descripcion' => 'Registrar pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.adelantos.create',
            'descripcion' => 'Registrar adelantos pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
        Permission::firstOrCreate([
            'name' => 'admin.administracion.employers.payments.delete',
            'descripcion' => 'Eliminar pagos del personal',
            'table' => 'Pagos personal',
            'module' => 'Recursos humanos',
            'orden' => '6'
        ])->syncRoles([$desarrollador, $admin]);
    }
}
