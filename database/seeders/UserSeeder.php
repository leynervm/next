<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Almacen;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $desarrollador = User::create([
            'document' => '74495914',
            'name' => 'LEINER VEGA MEJIA',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'admin' => 1,
        ]);

        $admin = User::create([
            'document' => '20538954099',
            'name' => 'NEXT TECHNOLOGIES',
            'email' => 'next@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::factory(10)->create();
    }
}
