<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Almacen;
use Nwidart\Modules\Facades\Module;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::create([
            'name' => 'Demo',
            'email' => 'demo@gmail.com',
            'password' => bcrypt('12345678'),
        ]);


        $sucursal = Sucursal::first();
        $almacen = Almacen::first();

        if ($sucursal && $almacen) {
            $admin->sucursals()->sync([$sucursal->id => [
                'default' => 1,
                'almacen_id' => $almacen->id,
            ]]);
        }

        // User::factory(30)->create();
    }
}
