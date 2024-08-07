<?php

namespace App\Console\Commands;

use App\Models\Box;
use App\Models\Caja;
use App\Models\Openbox;
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
        Openbox::open()->update([
            'status' => Openbox::INACTIVO, 'expiredate' => now('America/Lima')
        ]);

        Box::whereHas('user')->update(['status' => Box::INACTIVO, 'user_id' => null]);

        return $this->info("cajas cerradas correctamente");
    }
}
