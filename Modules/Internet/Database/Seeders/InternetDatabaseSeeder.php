<?php

namespace Modules\Internet\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InternetDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $this->call(RoleSeederTableSeeder::class);
        // $this->call("OthersTableSeeder");
    }
}
