<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Pricetype;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function redirect($driver)
    {

        $drivers = ['facebook', 'google'];

        if (in_array($driver, $drivers)) {
            return Socialite::driver($driver)->redirect();
        } else {
            return redirect()->route('login')->with('mensaje', "<b>$driver<b>  no está disponible para loguearse");
        }
    }

    public function callback(Request $request, $driver)
    {

        try {
            if ($request->get('error')) {
                return redirect()->route('login');
            }

            $drivers = ['facebook', 'google'];
            if (!in_array($driver, $drivers)) {
                return redirect()->route('login')->with('mensaje', "<b>$driver<b>  no está disponible para loguearse");
            }

            $user = Socialite::driver($driver)->stateless()->user();
            // dd($user->getName());
            if ($user) {
                DB::beginTransaction();
                try {

                    $email = $user->getEmail();
                    $empresa = mi_empresa();
                    $pricetype = getPricetypeAuth($empresa);

                    $user = User::firstOrCreate([
                        'email' => $email
                    ], [
                        'name' => $user->getName(),
                    ]);

                    $client = Client::where('email', $email)->first();
                    if ($client) {

                        $documentusers = User::where('document', trim($client->document))->count();

                        if (is_null($client->user_id) && $documentusers == 0) {
                            $user->document = $client->document;
                            $client->user_id = $user->id;
                            $client->save();
                            $user->save();
                        }
                    }

                    DB::commit();
                    auth()->login($user);
                    return redirect()->route('admin');
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $errorData = json_decode($responseBody);
            // dd( $errorData->error);
            return redirect()->route('login')->with('mensaje', "Error en la autenticación con $driver, " . $errorData->error->message ?? '');
        }
    }
}
