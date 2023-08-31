<?php

namespace App\Console\Commands;

use App\Models\Caja;
use App\Models\Opencaja;
use Illuminate\Console\Command;

class CerrarCajasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cerrarcajas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para cerrar las cajas';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Opencaja::CajasAbiertas()
            ->update([
                'status' => 1, 'expiredate' => now('America/Lima')
            ]);

        Caja::Abiertas()->update(['status' => 0, 'user_id' => null]);

        return $this->info("cajas cerradas correctamente");
    }
}
