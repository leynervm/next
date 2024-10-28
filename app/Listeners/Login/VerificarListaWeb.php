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

            if ($client && !$client->trashed()) {
                if (is_null($client->user_id)) {
                    $client->user()->associate($event->user);
                }

                //Lista por defecto login web
                $pricetype = Pricetype::login()->first();
                if (empty($pricetype)) {
                    $pricetype = Pricetype::orderBy('id', 'asc')->limit(1)->first();
                }

                // Verifico que lista vinculada es menor a lista web 
                // entonces actualizo a lista web
                if ($client->pricetype && $client->pricetype->isActivo()) {
                    if ($pricetype && ($client->pricetype_id < $pricetype->id)) {
                        $client->pricetype_id = $pricetype->id ?? null;
                    }
                } else {
                    $client->pricetype_id = $pricetype->id ?? null;
                }

                $client->save();
            }
        }
    }
}
