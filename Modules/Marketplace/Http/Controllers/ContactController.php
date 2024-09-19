<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Mail\EnviarFormContact;
use App\Models\Message;
use App\Rules\Recaptcha;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function index()
    {
        return view('marketplace::index');
    }

    public function store(Request $request)
    {

        $config_email = validarConfiguracionEmail();
        if (!$config_email->getData()->success) {
            $mensaje = response()->json([
                'title' => $config_email->getData()->error,
                'text' => null,
                'type' => 'error'
            ])->getData();
            return back()->with('message', $mensaje);
        }
        $validateData = $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email'],
            'descripcion' => ['required', 'string', 'min:6'],
            'g_recaptcha_response' => ['required', new Recaptcha()]
        ]);

        DB::beginTransaction();
        try {
            $messageContact = Message::create([
                'date' => now('America/Lima'),
                'name' => trim($request->name),
                'email' => trim($request->email),
                'to' => trim($request->email),
                'descripcion' => trim($request->descripcion)
            ]);
            DB::commit();
            $config_email = validarConfiguracionEmail();
            if ($config_email->getData()->success) {
                $mensaje = response()->json([
                    'title' => 'REGISTRADO Y ENVIADO CORRECTAMENTE',
                    'text' => null,
                    'type' => 'success'
                ])->getData();
                // Log::info('Disparando evento de EnviarFormContact que');
                Mail::to($messageContact->email)->send(new EnviarFormContact($messageContact));
            } else {
                $mensaje = response()->json([
                    'title' => 'REGISTRADO CORRECTAMENTE', //$config_email->getData()->error,
                    'text' => null,
                    'type' => 'success'
                ])->getData();
            }
            return redirect()->to('/')->with('message', $mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
