<?php

namespace Modules\Internet\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeederTableSeeder extends Seeder
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
            'name' => 'admin.network',
            'descripcion' => 'Administrar clientes de internet',
            'table' => 'Networks',
            'module' => 'Clientes Internet',
            'orden' => '8'
        ])->syncRoles([$desarrollador, $admin]);

        // $this->call("OthersTableSeeder");
    }
}
