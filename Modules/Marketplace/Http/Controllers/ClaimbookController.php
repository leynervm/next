<?php

namespace Modules\Marketplace\Http\Controllers;

use App\Mail\EnviarClaimbook;
use App\Models\Claimbook;
use App\Models\Sucursal;
use App\Rules\Recaptcha;
use App\Rules\ValidateDocument;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Nwidart\Modules\Facades\Module;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Dompdf\Options;
use Illuminate\Support\Facades\Mail;

class ClaimbookController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:admin.marketplace.claimbooks')->only('claimbooks');
        $this->middleware('can:admin.marketplace.claimbooks.show')->only('show');
    }


    public function index()
    {
        return view('marketplace::index');
    }


    public function create()
    {
        $sucursals = Sucursal::with('ubigeo')->orderBy('codeanexo', 'asc')->get();
        return view('marketplace::admin.claimbook.create', compact('sucursals'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $require_apoderado = false;
        if (isset($request->menor_edad)) {
            $require_apoderado = true;
        }

        $request->validate([
            'date' => ['required', 'date', 'before_or_equal:today'],
            'document' => ['required', 'numeric', 'min:8', new ValidateDocument,                'regex:/^\d{8}(?:\d{3})?$/',],
            'name' => ['required', 'string', 'min:6'],
            'direccion' => ['required', 'string', 'min:6'],
            'telefono' => ['required', 'numeric', 'min:9', 'digits:9',                'regex:/^\d{9}$/',],
            'email' => ['required', 'email',],
            'menor_edad' => ['nullable',],
            'document_apoderado' => [
                'nullable',
                Rule::requiredIf($require_apoderado),
                'numeric',
                'min:8',
                new ValidateDocument,
                'digits:8',
                'regex:/^\d{8}$/'
            ],
            'name_apoderado' => [
                'nullable',
                Rule::requiredIf($require_apoderado),
                'string',
                'min:6'
            ],
            'direccion_apoderado' => [
                'nullable',
                Rule::requiredIf($require_apoderado),
                'string',
                'min:6'
            ],
            'telefono_apoderado' => [
                'nullable',
                Rule::requiredIf($require_apoderado),
                'numeric',
                'min:9',
                'digits:9',
                'regex:/^\d{9}$/',
            ],
            'channelsale' => [
                'required',
                Rule::in([Claimbook::TIENDA_WEB, Claimbook::TIENDA_FISICA]),
            ],
            'tienda_compra' => [
                'nullable',
                Rule::requiredIf($request->channelsale == Claimbook::TIENDA_FISICA),
                'integer',
                'min:1',
                'exists:sucursals,id',
            ],
            'biencontratado' => ['required', 'string', 'min:3'],
            'descripcion_producto_servicio' => ['required', 'string',                'min:3'],
            'tipo_reclamo' => ['required', 'string', 'min:3'],
            'detalle_reclamo' => ['required', 'string',],
            'pedido' => ['nullable',],
            'g_recaptcha_response' => ['required', new Recaptcha()]
        ]);


        DB::beginTransaction();
        try {

            $sucursal_id = $request->channelsale == Claimbook::TIENDA_FISICA ? $request->tienda_compra : null;
            $correlativo = Claimbook::where('sucursal_id', $sucursal_id)->count('id') ?? 0;

            $datos = [
                'date' => $request->date,
                'serie' => 'LR01',
                'correlativo' => 1 + $correlativo,
                'document' => trim($request->document),
                'name' => trim($request->name),
                'direccion' => trim($request->direccion),
                'telefono' => trim($request->telefono),
                'email' => trim($request->email),
                'is_menor_edad' => $require_apoderado ? 1 : 0,
                'document_apoderado' => $require_apoderado ? trim($request->document_apoderado) : null,
                'name_apoderado' => $require_apoderado ? trim($request->name_apoderado) : null,
                'direccion_apoderado' => $require_apoderado ? trim($request->direccion_apoderado) : null,
                'telefono_apoderado' => $require_apoderado ? $request->telefono_apoderado : null,
                'biencontratado' => $request->biencontratado,
                'descripcion_producto_servicio' => trim($request->descripcion_producto_servicio),
                'tipo_reclamo' => $request->tipo_reclamo,
                'detalle_reclamo' => trim($request->detalle_reclamo),
                'pedido' => $request->pedido,
                'channelsale' => $request->channelsale,
                'sucursal_id' => $sucursal_id
            ];

            $claimbook = Claimbook::create($datos);
            DB::commit();
            Mail::to($claimbook->email)->send(new EnviarClaimbook($claimbook));
            // $mensaje = response()->json([
            //     'title' => 'Enviando correo a: ' . $comprobante->client->email,
            //     'icon' => 'success',
            // ])->getData();
            return redirect()->route('claimbook.resumen', $claimbook);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resumen(Claimbook $claimbook)
    {
        return view('marketplace::admin.claimbook.resumen', compact('claimbook'));
    }

    public function print(Claimbook $claimbook)
    {
        if (Module::isEnabled('Marketplace')) {
            $empresa = mi_empresa();
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isFontSubsettingEnabled', true);
            $options->set('isRemoteEnabled', true);
            $pdf = new PDF($options);
            $pdf = PDF::loadView('marketplace::pdf.claimbooks.a4', compact('empresa', 'claimbook'));
            return $pdf->stream();
        }
    }


    public function show(Claimbook $claimbook)
    {
        return view('admin.claimbooks.show');
    }


    public function edit($id)
    {
        return view('marketplace::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function claimbooks()
    {
        return view('admin.claimbooks.index');
    }
}
