<?php

namespace Modules\Caja\Database\Seeders;

use App\Models\Caja;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CajaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
        $firstDay = Carbon::now('America/Lima')->startOfMonth();
        $firstDay->setTime(0, 0, 0); // Establecer la hora a 00:00:00

        $firstDayNextMonth = Carbon::now('America/Lima')->startOfMonth()->addMonth();
        $firstDayNextMonth->setTime(0, 0, 0); // Establecer la hora a 00:00:00

        Caja::create([
            'date' => now('America/Lima'),
            'name' => 'CAJA NEXT AGOSTO 2023',
            'startmount' => 100,
            'amount' => 0,
            'datemonth' =>  Carbon::now('America/Lima')->format('Y-m'),
            'startdate' => $firstDay->format('Y-m-d H:i:s'),
            'expiredate' => $firstDayNextMonth->format('Y-m-d H:i:s'),
            'user_id' => 1
        ]);
    }
}
