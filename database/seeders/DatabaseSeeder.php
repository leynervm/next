<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        Storage::deleteDirectory('marcas');
        Storage::makeDirectory('marcas');

        Storage::deleteDirectory('equipos');
        Storage::makeDirectory('equipos');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        User::create([
            'name' => 'Demo',
            'email' => 'demo@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->call(MonedaSeeder::class);
    }
}
