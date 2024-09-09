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

        User::updateOrCreate([
            'document' => '20538954099',
            'name' => 'DEVELOPER ROOT',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now('America/Lima'),
            'admin' => 1,
        ]);

        // User::updateOrCreate([
        //     'document' => '00000000',
        //     'name' => 'LEINER VEGA MEJIA',
        //     'email' => 'leyner@gmail.com',
        //     'password' => bcrypt('12345678'),
        // ]);

        // User::updateOrCreate([
        //     'document' => '20538954099',
        //     'name' => 'NEXT TECHNOLOGIES',
        //     'email' => 'next@gmail.com',
        //     'password' => bcrypt('12345678'),
        // ]);

        // User::factory(10)->create();
    }
}
