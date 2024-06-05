<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Direccion;
use App\Models\Telephone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients  =  Client::factory(30)
            ->has(Telephone::factory()->count(1))
            ->has(Direccion::factory()->count(1))
            ->create();
    }
}
