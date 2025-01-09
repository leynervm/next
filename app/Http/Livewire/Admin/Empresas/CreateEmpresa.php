<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\FormatoPersonalizado;
use App\Models\Almacen;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\Ubigeo;
use App\Rules\ValidateFileKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Nwidart\Modules\Facades\Module;

class CreateEmpresa extends Component
{

    use WithFileUploads;

    public $icono, $logo, $cert, $idcert, $idlogo, $idicono;

    public $document, $name, $direccion, $telefono, $ubigeo_id,
        $estado, $condicion, $email, $web, $montoadelanto;
    public $usuariosol, $clavesol, $passwordcert, $sendmode, $clientid, $clientsecret;

    public $validatemail;
    public $dominiocorreo;
    public $uselistprice = 0;
    public $usepricedolar = 0;
    public $tipocambio;
    public $viewpricedolar = 0;
    public $tipocambioauto = 0;
    public $igv = '18.00';

    protected function rules()
    {
        return [
            'document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/'],
            'name' => ['required', 'string', 'min:3'],
            'direccion' => ['required', 'string', 'min:3'],
            'ubigeo_id' => ['required', 'integer', 'min:0', 'exists:ubigeos,id'],
            'estado' => ['required', 'string'],
            'condicion' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'web' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'telefono' => ['numeric', 'digits_between:6,9', 'regex:/^\d{6,9}$/'],
            'igv' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'logo' => ['nullable', 'file', 'mimes:jpg,bmp,png'],
            'icono' => ['nullable', 'file', 'mimes:ico'],

            // 'validatemail' => ['integer', 'min:0', 'max:1'],
            // 'dominiocorreo' => ['nullable', 'required_if:validatemail,1'],
            'uselistprice' => ['integer', 'min:0', 'max:1'],
            'usepricedolar' => ['integer', 'min:0', 'max:1'],
            'tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'viewpricedolar' => ['integer', 'min:0', 'max:1'],
            'tipocambioauto' => ['integer', 'min:0', 'max:1'],
            'sendmode' => ['required', 'integer', 'min:0', 'max:1'],
            'usuariosol' => ['nullable',  'required_if:sendmode,1', 'string'],
            'clavesol' => ['nullable',  'required_if:sendmode,1', 'string'],
            'passwordcert' => ['nullable',  'required_if:sendmode,1', 'string', 'min:3'],
            'clientid' => ['nullable',  'required_if:sendmode,1', 'string', 'min:3'],
            'clientsecret' => ['nullable', 'required_if:sendmode,1', 'string', 'min:3'],
            'cert' => ['nullable', 'required_if:sendmode,1', 'file',  new ValidateFileKey("pfx")],
        ];
    }

    public function mount()
    {
        $this->idlogo = rand();
        $this->idicono = rand();
        $this->idcert = rand();
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.empresas.create-empresa', compact('ubigeos'));
    }

    public function save()
    {

        if (Module::isDisabled('Facturacion')) {
            $this->sendmode = 0;
        }
        $this->uselistprice = $this->uselistprice == 1 ?  1 : 0;
        $this->usepricedolar = $this->usepricedolar == true ?  1 : 0;
        $this->viewpricedolar = $this->viewpricedolar == true ?  1 : 0;
        $this->tipocambioauto = $this->tipocambioauto == true ?  1 : 0;

        if ($this->usepricedolar == 0) {
            $this->usepricedolar = 0;
            $this->viewpricedolar = 0;
            $this->tipocambioauto = 0;
            $this->tipocambio = null;
        }

        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->validate();

        try {
            DB::beginTransaction();

            if (!Storage::directoryExists('images/company/')) {
                Storage::makeDirectory('images/company/');
            }

            if (!Storage::directoryExists(storage_path('app/company/pem/'))) {
                Storage::disk('local')->makeDirectory('company/pem');
            }

            $urlicono = $this->icono ?? null;
            $urlcert = $this->cert ?? null;

            if ($this->icono) {

                $extpublic = FormatoPersonalizado::getExtencionFile($this->icono->getClientOriginalName());
                $urlicono = uniqid() . '.' . $extpublic;
                // $this->icono->store(public_path('storage/images/company/' . $urlicono));
                Storage::putFileAs('images/company', $this->icono, $urlicono);
                if ($this->icono->getSize() > 1048576) { //1MB
                    // $compressedImage->destroy();
                    $this->addError('icono', 'La imagen excede el tamaño máximo permitido.');
                }
            }


            if ($this->cert) {
                $extcert = FormatoPersonalizado::getExtencionFile($this->cert->getClientOriginalName());
                $urlcert = 'cert_' . $this->document . '.' . $extcert;
                $this->cert->storeAs('company/cert/', $urlcert, 'local');
            }

            $empresa = Empresa::create([
                'document' => $this->document,
                'name' => $this->name,
                'estado' => $this->estado,
                'condicion' => $this->condicion,
                'direccion' => $this->direccion,
                'email' => $this->email,
                'web' => $this->web,
                'icono' => $urlicono,
                'montoadelanto' => $this->montoadelanto,
                'uselistprice' => $this->uselistprice,
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

            $sucursalprincipal = $empresa->sucursals()->create([
                'name' => $this->name,
                'direccion' => $this->direccion,
                'default' => Sucursal::DEFAULT,
                'codeanexo' => '0000',
                'ubigeo_id' => $this->ubigeo_id,
            ]);

            if ($this->telefono) {
                $empresa->telephones()->create([
                    'phone' => trim($this->telefono)
                ]);
            }

            if ($this->logo) {
                $compressedImage = Image::make($this->logo->getRealPath())
                    ->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 30);

                $urlLogo = uniqid() . '.' . $this->logo->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/images/company/' . $urlLogo));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
                }

                $empresa->image()->create([
                    'url' => $urlLogo,
                    'default' => 1
                ]);
            }

            DB::commit();
            $this->resetValidation();
            $this->reset();
            $this->dispatchBrowserEvent('created');
            if (session('redirect_url')) {
                $url = session('redirect_url');
                session()->forget('redirect_url');
                return redirect()->to($url);
            } else {
                return redirect()->route('admin.administracion.empresa');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function clearInput($input)
    {
        $this->reset([$input]);
    }

    public function searchclient()
    {
        $this->document = trim($this->document);
        $this->validate([
            'document' => 'required|numeric|digits:11|regex:/^\d{11}$/'
        ]);

        $this->name = null;
        $this->direccion = null;
        $this->telefono = null;
        $this->ubigeo_id = null;
        $this->estado = null;
        $this->condicion = null;
        $this->resetValidation();

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->direccion = $cliente->direccion;
                $this->estado = $cliente->estado;
                $this->condicion = $cliente->condicion;
                $this->ubigeo_id = $cliente->ubigeo_id;
            } else {
                $this->name = '';
                $this->direccion = '';
                $this->estado = '';
                $this->condicion = '';
                $this->ubigeo_id = null;
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

    public function clearCert()
    {
        $this->reset(['cert']);
        $this->idcert = rand();
    }

    public function clearLogo()
    {
        $this->reset(['logo']);
        $this->idlogo = rand();
    }

    public function clearIcono()
    {
        $this->reset(['icono']);
        $this->idicono = rand();
    }

    public function updatedLogo($file)
    {
        try {
            $url = $file->temporaryUrl();
        } catch (\Exception $e) {
            $this->reset(['logo']);
            $this->addError('logo', $e->getMessage());
            return;
        }
    }
}
