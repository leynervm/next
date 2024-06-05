<?php

namespace App\Http\Controllers;

use App\Mail\EnviarXMLMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Facturacion\Entities\Comprobante;

class EmailController extends Controller
{
    // public function enviarxml(Comprobante $comprobante)
    // {
    //     Mail::to('lvegam0413@gmail.com')->send(new EnviarXMLMailable());
    //     return "Mensaje enviado";
    // }
}
