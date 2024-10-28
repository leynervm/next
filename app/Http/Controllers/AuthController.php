<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Pricetype;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Nwidart\Modules\Facades\Module;

class AuthController extends Controller
{


    public function register()
    {
        return redirect()->route('login')->with('activeForm', 'register');
    }


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

            $socialUser  = Socialite::driver($driver)->stateless()->user();
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $errorData = json_decode($responseBody);
            return redirect()->route('login')->with('mensaje', "Error en la autenticación con $driver, " . $errorData->error->message ?? '');
        }

        if ($socialUser) {
            // DB::beginTransaction();
            // try {
            $email = $socialUser->getEmail();
            $userDB = User::withTrashed()->where('email', $email)->first();
            if ($userDB && $userDB->trashed()) {
                return redirect()->route('login')->with('mensaje', "__('The user is have down')");
            }

            $user = User::updateOrCreate(['email' => $email], [
                'name' => $socialUser->getName(),
            ]);

            // auth()->login($user, true);
            Auth::login($user, true);

            // if (Auth::attempt(['email' => $email])) {
            $request->session()->regenerate();
            if (Module::isEnabled('Marketplace')) {
                if (empty($user->document) || empty($user->password)) {
                    return redirect()->route('profile.complete');
                }
            }
            return redirect()->intended('/');
            // }

            // DB::commit();

            // if (Module::isEnabled('Marketplace')) {
            //     if (empty($user->document) || empty($user->password)) {
            //         return redirect()->route('profile.complete');
            //     }
            // }
            // return redirect()->intended('/');
            // } catch (\Exception $e) {
            //     DB::rollBack();
            //     throw $e;
            // } catch (\Throwable $e) {
            //     DB::rollBack();
            //     throw $e;
            // }
        }
    }
}
