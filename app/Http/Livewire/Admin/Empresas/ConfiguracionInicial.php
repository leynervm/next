<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\FormatoPersonalizado;
use App\Models\Acceso;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\ValidateFileKey;
use App\Traits\ManageArrayTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Str;

class ConfiguracionInicial extends Component
{

    use WithFileUploads, ManageArrayTrait;

    public $open = false;
    public $step = 1;

    public $empresa = [], $sucursals = [], $telephones = [], $selectedsucursals = [], $almacens = [];
    public $icono, $extencionicono, $image, $extencionimage, $extencionmarkagua, $cert, $idcert;

    public $document, $name, $direccion, $departamento, $provincia, $distrito, $telefono, $ubigeo_id,
        $estado, $condicion, $email, $web, $sendnode, $montoadelanto;
    public $whatsapp, $facebook, $youtube, $instagram, $tiktok;
    public $usuariosol, $clavesol, $passwordcert, $sendmode, $afectacionigv, $clientid, $clientsecret;

    public $validatemail;
    public $dominiocorreo;
    public $uselistprice = 0;
    public $viewpriceantes = 0;
    public $viewlogomarca = 0;
    public $viewespecificaciones = 0;
    public $viewtextopromocion = 0;
    public $usepricedolar = 0;
    public $tipocambio;
    public $viewpricedolar = 0;
    public $generatesku = 0;
    public $tipocambioauto = 0;
    public $igv = '18.00';
    public $usemarkagua = 0, $markagua, $alignmark = 'center', $widthmark = '100', $heightmark = '100';
    public $viewalmacens = 0, $viewalmacensdetalle = 0;

    public $namesucursal, $direccionsucursal, $ubigeosucursal_id, $typesucursal_id, $codeanexo;
    public $defaultsucursal = false;
    public $namealmacen;
    public $editindex;
    public $boxes = [], $seriecomprobantes = [];
    public $boxname, $apertura;
    public $typecomprobante_id, $serie, $seriecompleta, $indicio = '';
    public $contador = 0;

    public function mount()
    {
        if (module::isEnabled('Facturacion')) {
            $this->sendmode = Empresa::PRUEBA;
            $this->usuariosol = Empresa::USER_SOL_PRUEBA;
            $this->clavesol = Empresa::PASSWORD_SOL_PRUEBA;
            $this->clientid = Empresa::CLIENT_ID_GRE_PRUEBA;
            $this->clientsecret = Empresa::CLIENT_SECRET_GRE_PRUEBA;
            $this->passwordcert = Empresa::PASSWORD_CERT_PRUEBA;
        }

        if (module::isEnabled('Marketplace')) {
            $this->viewespecificaciones = Producto::VER_DETALLES;
        }

        $email = Config::get('mail.mailers.smtp.username');
        if (!empty($email)) {
            $this->email = $email;
        }
    }

    public function render()
    {
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        if (Module::isEnabled('Facturacion')) {
            $typecomprobantes = Typecomprobante::orderBy('code', 'asc')->get();
        } else {
            $typecomprobantes = Typecomprobante::Default()->orderBy('code', 'asc')->get();
        }
        return view('livewire.admin.empresas.configuracion-inicial', compact('ubigeos', 'typecomprobantes'));
    }

    public function openModal()
    {
        $this->open = true;
    }

    public function validatestep($step)
    {
        $acceso = Acceso::first();
        if (!$acceso || !$acceso->access()) {
            $mensaje = response()->json([
                'title' => "ACCESO DENEGADO PARA REGISTRAR DATOS DE LA EMPRESA.",
                'icon' => 'warning',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $this->uselistprice = $this->uselistprice == 1 ?  1 : 0;
        $this->usepricedolar = $this->usepricedolar == true ?  1 : 0;
        $this->viewpricedolar = $this->viewpricedolar == true ?  1 : 0;
        $this->viewpriceantes = $this->viewpriceantes == true ?  1 : 0;
        $this->viewlogomarca = $this->viewlogomarca == true ?  1 : 0;
        $this->generatesku = $this->generatesku == true ?  1 : 0;
        $this->tipocambioauto = $this->tipocambioauto == true ?  1 : 0;
        $this->usemarkagua = $this->usemarkagua == true ?  1 : 0;
        $this->viewespecificaciones = $this->viewespecificaciones == true ?  1 : 0;
        $this->viewalmacens = $this->viewalmacens == true ?  1 : 0;
        $this->viewalmacensdetalle = $this->viewalmacensdetalle == true ?  1 : 0;

        if ($this->usepricedolar == 0) {
            $this->usepricedolar = 0;
            $this->viewpricedolar = 0;
            $this->tipocambioauto = 0;
            $this->tipocambio = null;
        }
        // 20600129997
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);

        if ($step == 1) {
            $this->validate([
                'document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/'],
                'name' => ['required', 'string', 'min:3'],
                'direccion' => ['required', 'string', 'min:3'],
                'ubigeo_id' => ['required', 'integer', 'min:0', 'exists:ubigeos,id'],
                'estado' => ['nullable', 'string'],
                'condicion' => ['nullable', 'string'],
                'igv' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            ]);

            if (count($this->sucursals) > 0) {
                $establecimientos = collect($this->sucursals)->map(function ($item) {
                    if ($item['default'] == Sucursal::DEFAULT) {
                        $item['descripcion'] = 'TIENDA PRINCIPAL'; // . $this->name;
                        $item['direccion'] = $this->direccion;
                        $item['ubigeo_id'] = $this->ubigeo_id;
                        $ubigeo = Ubigeo::find($this->ubigeo_id);
                        $item['departamento'] = $ubigeo->region;
                        $item['provincia'] = $ubigeo->provincia;
                        $item['distrito'] = $ubigeo->distrito;
                    }
                    return $item;
                });

                $this->sucursals = $establecimientos->sortBy('codigo')->values()->toArray();
            }
        } elseif ($step == 2) {
            $this->validate([
                'uselistprice' => ['integer', 'min:0', 'max:1'],
                'usepricedolar' => ['integer', 'min:0', 'max:1'],
                'tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
                'viewpricedolar' => ['integer', 'min:0', 'max:1'],
                'tipocambioauto' => ['integer', 'min:0', 'max:1'],
                'viewpriceantes' => ['integer', 'min:0', 'max:1'],
                'viewlogomarca' => ['integer', 'min:0', 'max:1'],
                'viewespecificaciones' => ['integer', 'min:0', 'max:1'],
                'viewalmacens' => ['integer', 'min:0', 'max:1'],
                'viewalmacensdetalle' => ['integer', 'min:0', 'max:1'],
                'viewtextopromocion' => ['integer', 'min:0', 'max:3'],
                'generatesku' => ['integer', 'min:0', 'max:1'],
                'usemarkagua' => ['integer', 'min:0', 'max:1'],
                'markagua' => [
                    'nullable',
                    'required_if:usemarkagua,' . Empresa::OPTION_ACTIVE,
                    'string',
                    'regex:/^data:image\/png;base64,([A-Za-z0-9+\/=]+)$/'
                ],
                'alignmark' => ['nullable', 'required_if:usemarkagua,' . Empresa::OPTION_ACTIVE, 'string', 'max:25'],
                'widthmark' => ['nullable', 'required_if:usemarkagua,' . Empresa::OPTION_ACTIVE, 'integer', 'min:50', 'max:300'],
                'heightmark' => ['nullable', 'required_if:usemarkagua,' . Empresa::OPTION_ACTIVE, 'integer', 'min:50', 'max:300'],
            ], [
                'markagua.required_if'      => 'El campo :attribute es obligatorio.',
                'widthmark.required_if'     => 'El campo :attribute es obligatorio.',
                'heightmark.required_if'    => 'El campo :attribute es obligatorio.',
                'alignmark.required_if'     => 'El campo :attribute es obligatorio.',
            ]);
        } elseif ($step == 3) {
            $this->validate([
                'almacens' => ['nullable', Rule::requiredIf(module::isEnabled('Ventas') || module::isEnabled('Almacen')), 'array', 'min:1'],
                'telephones' => ['required', 'array', 'min:1'],
                'email' => ['nullable', 'email'],
                'web' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
                'whatsapp' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
                'facebook' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
                'youtube' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
                'instagram' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
                'tiktok' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            ], [
                'almacens.required' => 'No se han agregado almacenes',
                'telephones.required' => 'No se han agregado teléfonos de contacto',
            ]);

            if (module::isEnabled('Ventas') || module::isEnabled('Almacen')) {
                if (module::isDisabled('Almacen')) {
                    if (count($this->almacens) > 1) {
                        $this->addError('namealmacen', 'Solamente se permite agregar un almacén');
                        return false;
                    }
                }
            }
        } elseif ($step == 4) {
            $this->validate([
                'selectedsucursals' => ['required', 'array', 'min:1']
            ], [
                'selectedsucursals.required' => 'Por favor seleccione sucursales a registrar',
            ]);

            if (!$acceso->unlimit() || $this->document != '20538954099') {
                // dd(count($this->selectedsucursals), $this->selectedsucursals, $this->sucursals);
                if (count($this->selectedsucursals) > $acceso->limitsucursals) {
                    $mensaje = response()->json([
                        'title' => "SOLO PUEDE SELECCIONR UN MÁXIMO DE " . $acceso->limitsucursals . " SUCURSALES",
                        'icon' => 'warning',
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $mensaje = null;
            foreach ($this->sucursals as $item) {
                if (in_array($item['codigo'], $this->selectedsucursals)) {
                    if (strlen(trim($item['codigo'])) !== 4 || !is_numeric(trim($item['codigo']))) {
                        $mensaje = "CÓDIGO DE SUCURSAL " . $item['descripcion'] . " CON DIRECCIÓN " . $item['direccion'] . " ES INVÁLIDO.";
                        break;
                    }

                    if (count($item['boxes']) == 0) {
                        $mensaje = "SUCURSAL " . $item['descripcion'] . " NO CONTIENE CAJAS DE PAGO AGREGADAS.";
                        break;
                    }
                    if (count($item['seriecomprobantes']) == 0) {
                        $mensaje = "SUCURSAL " . $item['descripcion'] . " NO CONTIENE COMPROBANTES DE VENTA AGREGADAS.";
                        break;
                    }
                }
            }

            if (!empty($mensaje)) {
                $mensaje = response()->json([
                    'title' => $mensaje,
                    'icon' => 'warning',
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }
        } else {
            $this->validate([
                'afectacionigv' => ['required', 'integer', 'min:0', 'max:1'],
                'sendmode' => ['nullable', Rule::requiredIf(module::isEnabled('Facturacion')), 'integer', 'min:0', 'max:1'],
                'cert' => [
                    'nullable',
                    Rule::requiredIf(module::isEnabled('Facturacion') && $this->sendmode == Empresa::PRODUCCION),
                    'file',
                    new ValidateFileKey("pfx")
                ],
                'usuariosol' => ['nullable', Rule::requiredIf(module::isEnabled('Facturacion')), 'string', 'min:4'],
                'clavesol' => ['nullable', Rule::requiredIf(module::isEnabled('Facturacion')), 'string', 'min:4'],
                'passwordcert' => ['nullable', Rule::requiredIf(module::isEnabled('Facturacion')), 'string', 'min:6'],
                'clientid' => ['nullable', Rule::requiredIf(module::isEnabled('Facturacion')), 'string', 'min:6'],
                'clientsecret' => ['nullable', Rule::requiredIf(module::isEnabled('Facturacion')), 'string', 'min:6'],

            ]);
        }

        $this->empresa =  [
            'document' => $this->document,
            'name' => $this->name,
            'estado' => $this->estado,
            'condicion' => $this->condicion,
            'direccion' => $this->direccion,
            'departamento' => $this->departamento,
            'provincia' => $this->provincia,
            'distrito' => $this->distrito,
            'email' => $this->email,
            'web' => $this->web,
            'whatsapp' => $this->whatsapp,
            'facebook' => $this->facebook,
            'youtube' => $this->youtube,
            'instagram' => $this->instagram,
            'tiktok' => $this->tiktok,
            // 'icono' => $urlicono,
            'montoadelanto' => $this->montoadelanto,
            'uselistprice' => $this->uselistprice,
            'viewpriceantes' => $this->viewpriceantes,
            'viewlogomarca' => $this->viewlogomarca,
            'viewespecificaciones'  => $this->viewespecificaciones,
            'viewalmacens'  => $this->viewalmacens,
            'viewalmacensdetalle'  => $this->viewalmacensdetalle,
            'generatesku' => $this->generatesku,
            'viewtextopromocion' => $this->viewtextopromocion,
            'usemarkagua' => $this->usemarkagua,
            'markagua' => $this->markagua,
            'alignmark' => $this->alignmark,
            'widthmark' => $this->widthmark,
            'heightmark' => $this->heightmark,
            'usepricedolar' => $this->usepricedolar,
            'viewpricedolar' => $this->viewpricedolar,
            'tipocambio' => $this->tipocambio,
            'tipocambioauto' => $this->tipocambioauto,
            'default' => 1,
            'igv' => $this->igv,
            'ubigeo_id' => $this->ubigeo_id,
            'sendmode' => $this->sendmode,
            'passwordcert' => $this->passwordcert,
            'usuariosol' => $this->usuariosol,
            'clavesol' => $this->clavesol,
            'clientid' => $this->clientid,
            'clientsecret' => $this->clientsecret,
            // 'cert' => $urlcert,
        ];

        $this->step++;
    }

    public function save()
    {

        $acceso = Acceso::first();
        if (!$acceso || !$acceso->access()) {
            $mensaje = response()->json([
                'title' => "ACCESO DENEGADO PARA REGISTRAR DATOS DE LA EMPRESA.",
                'icon' => 'warning',
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        $urlicono = $this->icono ?? null;
        $urlcert = $this->cert ?? null;

        if ($this->icono) {
            $imageIcono = $this->icono;
            $urlicono = uniqid() . '.' . $this->extencionicono;
            $imagePath = public_path('storage/images/company/' . $urlicono);

            list($type, $imageIcono) = explode(';', $imageIcono);
            list(, $imageIcono) = explode(',', $imageIcono);
            $imageIcono = base64_decode($imageIcono);
            file_put_contents($imagePath, $imageIcono);
        }

        if ($this->cert) {
            $extcert = FormatoPersonalizado::getExtencionFile($this->cert->getClientOriginalName());
            $urlcert = 'cert_' . $this->document . '.' . $extcert;
            $this->cert->storeAs('company/cert/', $urlcert, 'local');
        }

        $markURL = null;
        if ($this->markagua) {
            $imageMark = $this->markagua;
            list($type, $imageMark) = explode(';', $imageMark);
            list(, $imageMark) = explode(',', $imageMark);
            $imageMark = base64_decode($imageMark);

            if (!Storage::directoryExists('images/company/')) {
                Storage::makeDirectory('images/company/');
            }

            $compressedMark = Image::make($imageMark)
                ->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->orientate()->encode('webp', 90);

            if ($compressedMark->filesize() > 1048576) { //1MB
                $compressedMark->destroy();
                $this->addError('markagua', 'La imagen excede el tamaño máximo permitido.');
                return false;
            }
            $markURL = uniqid('markagua_') . '.webp';
            $compressedMark->save(public_path('storage/images/company/' . $markURL));
        }

        try {
            $empresa = Empresa::create([
                'document' => $this->document,
                'name' => $this->name,
                'estado' => $this->estado,
                'condicion' => $this->condicion,
                'direccion' => $this->direccion,
                'email' => $this->email,
                'web' => $this->web,
                'whatsapp' => $this->whatsapp,
                'facebook' => $this->facebook,
                'instagram' => $this->instagram,
                'tiktok' => $this->tiktok,
                'icono' => $urlicono,
                'montoadelanto' => $this->montoadelanto,
                'uselistprice' => $this->uselistprice,
                'viewpriceantes' => $this->viewpriceantes,
                'viewlogomarca' => $this->viewlogomarca,
                'generatesku' => $this->generatesku,
                'viewalmacens' => $this->viewalmacens,
                'viewalmacensdetalle' => $this->viewalmacensdetalle,
                'viewtextopromocion' => $this->viewtextopromocion,
                'usemarkagua' => $this->usemarkagua,
                'markagua' => $markURL,
                'alignmark' => $this->alignmark,
                'widthmark' => $this->widthmark,
                'heightmark' => $this->heightmark,
                'usepricedolar' => $this->usepricedolar,
                'viewpricedolar' => $this->viewpricedolar,
                'tipocambio' => $this->tipocambio,
                'tipocambioauto' => $this->tipocambioauto,
                'default' => 1,
                'igv' => $this->igv,
                'ubigeo_id' => $this->ubigeo_id,
                'sendmode' => $this->sendmode,
                'passwordcert' => $this->passwordcert,
                'usuariosol' => $this->usuariosol,
                'clavesol' => $this->clavesol,
                'clientid' => $this->clientid,
                'clientsecret' => $this->clientsecret,
                'cert' => $urlcert,
            ]);

            if (count($this->almacens) > 0) {
                foreach ($this->almacens as $item) {
                    Almacen::create(['name' => $item]);
                }
            }

            if ($this->image) {
                $imageLogo = $this->image;
                list($type, $imageLogo) = explode(';', $imageLogo);
                list(, $imageLogo) = explode(',', $imageLogo);
                $imageLogo = base64_decode($imageLogo);

                $compressedImage = Image::make($imageLogo)
                    ->resize(400, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('webp', 90);

                $urlLogo = uniqid() . '.webp';
                $compressedImage->save(public_path('storage/images/company/' . $urlLogo));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('image', "La imagen excede el tamaño permitido 1MB, tamaño actual " . $compressedImage->filesize());
                    return false;
                }

                $empresa->image()->create([
                    'url' => $urlLogo,
                    'default' => 1
                ]);
            }

            if (count($this->selectedsucursals) > 0) {
                $filteredSucursals = array_filter($this->sucursals, function ($item) {
                    return in_array($item['codigo'], $this->selectedsucursals);
                });

                foreach ($filteredSucursals as $item) {
                    $sucursal = $empresa->sucursals()->create([
                        'name' => $item['descripcion'],
                        'direccion' => $item['direccion'],
                        'default' => $item['default'],
                        'codeanexo' => $item['codigo'],
                        'typesucursal_id' => $item['typesucursal_id'],
                        'ubigeo_id' => $item['ubigeo_id'],
                    ]);

                    if (module::isEnabled('Almacen') || module::isEnabled('Ventas')) {
                        // if (module::isDisabled('Almacen')) {
                        $almacens = Almacen::all()->pluck('id')->toArray();
                        if ($sucursal->isDefault()) {
                            $sucursal->almacens()->sync($almacens);
                        }
                        // }
                    }

                    if (count($item['boxes']) > 0) {
                        foreach ($item['boxes'] as $box) {
                            $sucursal->boxes()->create([
                                'name' => $box['name'],
                                'apertura' => $box['apertura']
                            ]);
                        }
                    }

                    if (count($item['seriecomprobantes']) > 0) {
                        foreach ($item['seriecomprobantes'] as $serie) {
                            $sucursal->seriecomprobantes()->create([
                                'serie' => $serie['serie'],
                                'code' => $serie['code'],
                                'contador' => $serie['contador'],
                                'contadorprueba' => 0,
                                'default' => $serie['default'],
                                'typecomprobante_id' => $serie['typecomprobante_id'],
                            ]);
                        }
                    }
                }
            }

            if (count($this->telephones) > 0) {
                foreach ($this->telephones as $item) {
                    $empresa->telephones()->create([
                        'phone' => $item
                    ]);
                }
            }

            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['step']);
            $this->dispatchBrowserEvent('toast', toastJSON('INICIAR'));
            return redirect()->route('admin');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function searchclient()
    {
        $this->document = trim($this->document);
        $this->validate([
            'document' => 'required|numeric|digits:11|regex:/^\d{11}$/'
        ]);

        $this->resetValidation();
        $this->reset([
            'name',
            'direccion',
            'telefono',
            'ubigeo_id',
            'departamento',
            'provincia',
            'distrito',
            'estado',
            'condicion',
            'selectedsucursals'
        ]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            //  dd($cliente);
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->direccion = $cliente->direccion;
                $this->estado = $cliente->estado;
                $this->condicion = $cliente->condicion;
                $this->ubigeo_id = $cliente->ubigeo_id;
                $this->departamento = $cliente->region;
                $this->provincia = $cliente->provincia;
                $this->distrito = $cliente->distrito;

                $establecimientos = [];
                $principal[] = [
                    'id' => Str::uuid(),
                    'descripcion' => 'TIENDA PRINCIPAL ' . $cliente->name,
                    'direccion' => $cliente->direccion,
                    'ubigeo_id' => $cliente->ubigeo_id,
                    'typesucursal_id' => 1,
                    'departamento' => $cliente->region,
                    'provincia' => $cliente->provincia,
                    'distrito' => $cliente->distrito,
                    'cod_tipo' => 'MA',
                    'tipo' => 'CASA MATRIZ',
                    'codigo' => '0000',
                    'default' => Sucursal::DEFAULT,
                    'boxes' => [],
                    'seriecomprobantes' => [],
                ];

                $locales = json_decode(json_encode($cliente->establecimientos), true) ?? [];
                $arrLocales = $this->mapToArrayEstablecimientos($locales, true);
                $establecimientos = array_map(function ($item) {
                    $others = [
                        'id' => Str::uuid(),
                        'default' => false,
                        'descripcion' => $item['codigo'],
                        'typesucursal_id' => null,
                        'boxes' => [],
                        'seriecomprobantes' => [],
                    ];
                    return array_merge($item, $others);
                }, $arrLocales);

                // $establecimientos[] = $principal;
                $collect = collect(array_values(array_merge_recursive($principal, $establecimientos)));
                $this->selectedsucursals[] = '0000';
                $this->sucursals = $collect->sortBy('codigo')->values()->toArray();
                // dd($this->sucursals);
            } else {
                $this->name = '';
                $this->direccion = '';
                $this->estado = '';
                $this->condicion = '';
                $this->ubigeo_id = null;
                $this->departamento = '';
                $this->provincia = '';
                $this->distrito = '';
                $this->selectedsucursals = [];
                $this->sucursals = [];
                $this->addError('document', $cliente->error);
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function addalmacen()
    {
        if (module::isEnabled('Ventas') || module::isEnabled('Almacen')) {
            $this->namealmacen =  mb_strtoupper(trim($this->namealmacen), "UTF-8");
            $this->validate([
                'namealmacen' => ['required', 'string', 'min:3']
            ]);

            if (module::isDisabled('Almacen')) {
                if (count($this->almacens) >= 1) {
                    $this->addError('namealmacen', 'Ya se agregaron datos de almacén');
                    return false;
                }
            }

            $this->almacens[] = $this->namealmacen;
            $this->reset(['namealmacen']);
        }
    }

    public function removealmacen($index)
    {
        unset($this->almacens[$index]);
        $this->almacens = array_values($this->almacens);
    }

    public function addphone()
    {
        $this->telefono = trim($this->telefono);
        $this->validate([
            'telefono' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
        ]);

        if (in_array($this->telefono, $this->telephones)) {
            $this->addError('telefono', 'Teléfono ya se encuentra agregado');
            return false;
        }
        $this->telephones[] = $this->telefono;
        $this->reset(['telefono']);
    }

    public function removephone($index)
    {
        unset($this->telephones[$index]);
        $this->telephones = array_values($this->telephones);
    }

    public function openmodalsucursal()
    {
        $this->resetValidation();
        $this->reset([
            'defaultsucursal',
            'typesucursal_id',
            'namesucursal',
            'direccionsucursal',
            'codeanexo',
            'ubigeosucursal_id',
            'typecomprobante_id',
            'indicio',
            'serie',
            'seriecompleta',
            'contador',
            'seriecomprobantes',
            'boxes',
            'boxname',
            'apertura',
            'editindex'
        ]);
        $this->open = true;
    }

    public function addbox()
    {
        $this->boxname = mb_strtoupper(trim($this->boxname), "UTF-8");
        $this->apertura = number_format($this->apertura, 2, '.', '');
        $this->validate([
            'boxname' => ['required', 'string', 'min:3'],
            'apertura' => ['required', 'numeric', 'gt:0', 'decimal:0,2']
        ]);

        if (count($this->boxes) > 0) {
            $existscode = $this->existsInArrayByKey($this->boxes, 'name', $this->boxname);
            if ($existscode) {
                $this->addError('boxname', 'Ya existe una caja de pago con el mismo nombre');
                return false;
            }
        }

        $box = [
            'name' => $this->boxname,
            'apertura' => $this->apertura,
            'id' => Str::uuid()
        ];
        $this->boxes[] = $box;
        $this->reset(['boxname', 'apertura']);
        $this->resetValidation();
    }

    public function removebox(string $id)
    {
        $this->boxes = $this->removeFromArrayByKey($this->boxes, 'id', $id);
        if ($this->editindex) {
            $this->sucursals = array_values(array_map(function ($item) {
                if (toStrLowercase($item['id']) == toStrLowercase($this->editindex)) {
                    $item['boxes'] = $this->boxes;
                }
                return $item;
            }, $this->sucursals));
        }
        $this->resetValidation();
    }

    public function editsucursal(string $id)
    {
        // dd($id);
        $this->resetValidation();
        $this->reset(['typecomprobante_id', 'serie', 'indicio', 'seriecompleta', 'contador', 'boxname', 'apertura']);
        if (count($this->selectedsucursals) > 0) {
            $sucursal = $this->findInArrayByKey($this->sucursals, 'id', $id);
            if ($sucursal) {
                $this->editindex = $id;
                $this->namesucursal =  mb_strtoupper(trim($sucursal["descripcion"]), "UTF-8");
                $this->direccionsucursal =  mb_strtoupper(trim($sucursal["direccion"]), "UTF-8");
                $this->ubigeosucursal_id = $sucursal["ubigeo_id"];
                $this->codeanexo = $sucursal["codigo"];
                $this->boxes = $sucursal["boxes"];
                $this->seriecomprobantes = $sucursal["seriecomprobantes"];
                $this->open = true;
            }
        }
    }

    public function addsucursal($closemodal = false)
    {

        $this->namesucursal = mb_strtoupper(trim($this->namesucursal), "UTF-8");
        $this->direccionsucursal = mb_strtoupper(trim($this->direccionsucursal), "UTF-8");
        $this->codeanexo = trim($this->codeanexo);

        $this->validate([
            'namesucursal' => ['required', 'min:3', 'max:255', new CampoUnique('sucursals', 'name', null, true),],
            'direccionsucursal' => ['required', 'string', 'min:3', 'max:255'],
            'typesucursal_id' => ['nullable', 'integer', 'min:1', 'exists:typesucursals,id',],
            'ubigeosucursal_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id',],
            'codeanexo' => ['required', 'numeric', 'digits:4', new CampoUnique('sucursals', 'codeanexo', null, true),],
            'defaultsucursal' => ['required', 'boolean', 'min:0', 'max:1', new DefaultValue('sucursals', 'default', null, true)],
            'seriecomprobantes' => ['required', 'array', 'min:1'],
            'boxes' => ['required', 'array', 'min:1'],
        ], [
            'boxes.required' => 'No se han agregado cajas de pago',
            'seriecomprobantes.required' => 'No se han agregado comprobantes de venta',
        ]);

        if (count($this->sucursals) > 0) {
            $sucursals = array_filter($this->sucursals, function ($item) {
                return (toStrLowercase($this->editindex) !== toStrLowercase($item['id']));
            });

            $existscode = $this->existsInArrayByKey($sucursals, 'codigo', $this->codeanexo);
            if ($existscode) {
                $this->addError('codeanexo', 'El valor de código de anexo ya está agregado.');
                return false;
            }

            $existsname = $this->existsInArrayByKey($sucursals, 'descripcion', $this->namesucursal);

            if ($existsname) {
                $this->addError('namesucursal', 'El nombre de sucursal ya está agregado.');
                return false;
            }

            if ($existscode || $existsname) {
                return false;
            }

            if ($this->defaultsucursal) {
                $existsdefault = $this->existsInArrayByKey($sucursals, 'default', true, false);
                if ($existsdefault) {
                    $updatedSucursals = array_values(array_map(function ($item) {
                        return array_merge($item, ['default' => false]);
                    }, $sucursals));

                    $this->sucursals = $updatedSucursals;
                }
            }
        }

        $departamento = '';
        $provincia = '';
        $distrito = '';
        if ($this->ubigeosucursal_id) {
            $ubigeo = Ubigeo::find($this->ubigeosucursal_id);
            $departamento = $ubigeo->departamento;
            $provincia = $ubigeo->provincia;
            $distrito = $ubigeo->distrito;
        }

        $editSucursal = $this->editindex ? $this->findInArrayByKey($this->sucursals, 'id', $this->editindex) : null;
        $default = !empty($editSucursal) ? (bool) $editSucursal['default'] : $this->defaultsucursal;
        $sucursal_updated = [
            'descripcion' => $this->namesucursal,
            'direccion' => $this->direccionsucursal,
            'ubigeo_id' => $this->ubigeosucursal_id,
            'typesucursal_id' => null,
            'departamento' => $departamento,
            'provincia' => $provincia,
            'distrito' => $distrito,
            'cod_tipo' => '',
            'tipo' => '',
            'codigo' => $this->codeanexo,
            'default' => $default,
            'boxes' => array_values($this->boxes),
            'seriecomprobantes' => array_values($this->seriecomprobantes)
        ];

        if (empty($this->editindex)) {
            $sucursal_updated['id'] = Str::uuid();
            $this->sucursals[] = $sucursal_updated;
        } else {
            $this->sucursals = array_values(array_map(function ($item) use ($sucursal_updated) {
                return $item['id'] == $this->editindex
                    ? array_merge($item, $sucursal_updated)
                    : $item;
            }, $this->sucursals));
        }
        if ($editSucursal) {
            $selecteds = $this->selectedsucursals ?? [];
            if (!in_array($this->codeanexo, $selecteds)) {
                $selecteds[] = $this->codeanexo;
                $this->selectedsucursals = array_values(array_filter($selecteds, function ($codigo) use ($editSucursal) {
                    return ($editSucursal['codigo'] != $codigo);
                }));
            }
        }
        $this->reset(['open', 'editindex', 'boxes', 'seriecomprobantes', 'defaultsucursal', 'typesucursal_id', 'namesucursal', 'direccionsucursal', 'codeanexo', 'ubigeosucursal_id']);
    }

    public function removesucursal(string $id)
    {
        $selecteds = $this->selectedsucursals ?? [];
        $sucursal = $this->findInArrayByKey($this->sucursals, 'id', $id);
        if (in_array($sucursal['codigo'], $this->selectedsucursals)) {
            $this->selectedsucursals = array_values(array_filter($selecteds, function ($codigo) use ($sucursal) {
                return ($sucursal['codigo'] != $codigo);
            }));
        }
        $this->sucursals = $this->removeFromArrayByKey($this->sucursals, 'id', $id);
        $this->resetValidation();
    }

    public function addseriecomprobante()
    {
        $this->serie =  mb_strtoupper(trim($this->serie), "UTF-8");
        $this->seriecompleta =  mb_strtoupper(trim($this->indicio . $this->serie), "UTF-8");
        $regex = '';
        $code = null;
        $default = 0;
        $mensaje = 'El campo serie es obligatorio';

        if ($this->typecomprobante_id) {
            $typecomprobante = Typecomprobante::find($this->typecomprobante_id);

            switch ($typecomprobante->code) {
                case '01':
                    $regex = 'regex:/^F[A-Z0-9][0-9][1-9]$/';
                    $code = null;
                    $default = count($this->seriecomprobantes) == 0 ? 1 : 0;
                    $mensaje = 'El campo serie debe tener la combinación F[A-Z0-9][0-9][1-9]';
                    break;
                case '03':
                    $regex = 'regex:/^B[A-Z0-9][0-9][1-9]$/';
                    $code = null;
                    $default = count($this->seriecomprobantes) == 0 ? 1 : 0;
                    $mensaje = 'El campo serie debe tener la combinación B[A-Z0-9][0-9][1-9]';
                    break;
                case '07':
                    $regex = $typecomprobante->referencia == '01' ? 'regex:/^F[A-Z0-9][0-9][1-9]$/' : 'regex:/^B[A-Z0-9][0-9][1-9]$/';
                    $code = $typecomprobante->referencia;
                    $mensaje = $typecomprobante->referencia == '01' ? 'El campo serie debe tener la combinación F[A-Z0-9][0-9][1-9]' : 'El campo serie debe tener la combinación B[A-Z0-9][0-9][1-9]';
                    break;
                case '09':
                    $regex = $typecomprobante->sendsunat ? 'regex:/^T[A-Z0-9][0-9][1-9]$/' : 'regex:/^E[A-Z0-9][0-9][1-9]$/';
                    $mensaje = $typecomprobante->sendsunat ? 'El campo serie debe tener la combinación T[A-Z0-9][0-9][1-9]' : 'El campo serie debe tener la combinación E[A-Z0-9][0-9][1-9]';
                    $code = null;
                    break;
                case 'VT':
                    $regex = 'regex:/^TK[0-9][1-9]$/';
                    $default = count($this->seriecomprobantes) == 0 ? 1 : 0;
                    $mensaje = 'El campo serie debe tener la combinación TK[0-9][1-9]';
                    $code = null;
                    break;
                default:
                    $regex = '';
                    $code = null;
                    $mensaje = 'El campo serie es obligatorio';
                    break;
            }
        }

        $this->validate([
            'typecomprobante_id' => ['required', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'seriecompleta' => ['required', 'string', 'size:4', $regex],
            'contador' => ['required', 'integer', 'min:0']
        ], [
            'serie.regex' => $mensaje
        ]);

        $existstypecomp = false;
        $existsserie = false;
        if (count($this->seriecomprobantes) > 0) {
            $seriecomprobantes = collect($this->seriecomprobantes);
            $existstypecomp = $seriecomprobantes->pluck('typecomprobante_id')->contains($this->typecomprobante_id);
            if ($existstypecomp) {
                $this->addError('typecomprobante_id', 'Ya se agregó el mismo tipo de comprobante');
            }
        }

        //La serie hay que validar en todas las sucursales
        if (count($this->sucursals) > 0) {
            $existsserie = collect($this->sucursals)->contains(function ($sucursal) {
                return collect($sucursal['seriecomprobantes'])->contains('serie', $this->seriecompleta);
            });
            if ($existsserie) {
                $this->addError('seriecompleta', 'Ya existe un comprobante con la misma serie');
            }
        }

        if ($existstypecomp || $existsserie) {
            return false;
        }

        $seriecomprobante = [
            'serie' => $this->seriecompleta,
            'contador' => $this->contador,
            'code' => $code,
            'typecomprobante_id' => $this->typecomprobante_id,
            'typecomprobante' => $typecomprobante,
            'default' => $default,
        ];
        $this->seriecomprobantes[] = $seriecomprobante;
        if (count($this->sucursals) > 0) {
            //Asignar default cuando hay mas de 0 sucursals 
            //xq cuando es first lo hace en swich case
            Self::setDefaultserie();
        }
        $this->reset(['serie', 'seriecompleta', 'indicio', 'contador', 'typecomprobante_id']);
        $this->resetValidation();
    }

    public function setDefaultserie()
    {
        $exists = collect($this->seriecomprobantes)->pluck('default')->contains('1');
        if (!$exists) {
            foreach ($this->seriecomprobantes as $key => $serie) {
                if (in_array($serie['typecomprobante']['code'], ['01', '03', 'VT'])) {
                    $this->seriecomprobantes[$key]['default'] = '1';
                    break;
                }
            }
        }
    }

    public function removeserie($indice)
    {
        if ($indice >= 0) {
            unset($this->seriecomprobantes[$indice]);
            $this->seriecomprobantes = array_values($this->seriecomprobantes);
            Self::setDefaultserie();

            if (!is_null($this->editindex)) {
                $this->sucursals[$this->editindex]["seriecomprobantes"] = array_values($this->seriecomprobantes);
            }
        }
        $this->resetValidation();
    }

    public function clearCert()
    {
        $this->reset(['cert']);
        $this->resetValidation();
    }

    // 20600129997
}
