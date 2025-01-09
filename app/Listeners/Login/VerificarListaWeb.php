<?php

namespace App\Listeners\Login;

use App\Models\Client;
use App\Models\Pricetype;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class VerificarListaWeb
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $empresa = view()->shared('empresa');

        if ($empresa && $empresa->usarLista()) {
            $campo = !empty($event->user->document) ? 'document' : 'email';
            $client = Client::withTrashed()->where($campo, $event->user->{$campo})->first();

            // Si esta eliminado restauramos
            if ($client && !$client->trashed()) {
                $client->restore();
                $client->direccions()->restore();
            }

            if (is_null($client->user_id)) {
                $client->user()->associate($event->user);
            }

            //Lista por defecto login web
            $pricetype = Pricetype::activos()->login()->first();
            if (empty($pricetype)) {
                $pricetype = Pricetype::activos()->orderBy('id', 'asc')->limit(1)->first();
            }

            // Verifico que lista vinculada es menor a lista web 
            // entonces actualizo a lista web
            if (!empty($pricetype)) {
                if (is_null($client->pricetype_id) || ($client->pricetype_id < $pricetype->id)) {
                    $client->pricetype_id = $pricetype->id;
                    $client->save();
                }
            }
        }
    }
}
