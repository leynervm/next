<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\FormatoPersonalizado;
use App\Helpers\GetClient;
use App\Models\Empresa;
use App\Models\Ubigeo;
use App\Rules\ValidateFileKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateEmpresa extends Component
{

    use WithFileUploads;


    public $isUploadingLogo = false;
    public $isUploadingIcono = false;
    public $isUploadingPublicKey = false;
    public $isUploadingPrivateKey = false;

    public $icono, $logo, $iconobase64;
    protected $listeners = ['erroricono', 'iconloaded'];

    public $idlogo, $idicono, $idpublickey, $publickey, $idprivatekey, $privatekey;
    public $document, $name, $direccion, $telefono, $ubigeo_id,
        $estado, $condicion, $email, $web, $montoadelanto;
    public $usuariosol, $clavesol;

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
            'usuariosol' => ['nullable', 'string'],
            'clavesol' => ['nullable', 'string'],
            'logo' => ['nullable', 'file', 'mimes:jpg,bmp,png'],
            'icono' => ['nullable', 'file', 'mimes:ico'],
            'publickey' => ['nullable', 'file', new ValidateFileKey("pem")],
            'privatekey' => ['nullable', 'file',  new ValidateFileKey("pem")],
            // 'validatemail' => ['integer', 'min:0', 'max:1'],
            // 'dominiocorreo' => ['nullable', 'required_if:validatemail,1'],
            'uselistprice' => ['integer', 'min:0', 'max:1'],
            'usepricedolar' => ['integer', 'min:0', 'max:1'],
            'tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'viewpricedolar' => ['integer', 'min:0', 'max:1'],
            'tipocambioauto' => ['integer', 'min:0', 'max:1']
        ];
    }

    public function mount()
    {
        $this->idlogo = rand();
        $this->idicono = rand();
        $this->idpublickey = rand();
        $this->idprivatekey = rand();
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.empresas.create-empresa', compact('ubigeos'));
    }

    public function save()
    {

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
            $urlpublickey = $this->publickey ?? null;
            $urlprivatekey = $this->privatekey ?? null;

            if ($this->icono) {

                $extpublic = FormatoPersonalizado::getExtencionFile($this->icono->getClientOriginalName());
                $urlicono = uniqid() . '.' . $extpublic;
                // $this->icono->store(public_path('storage/images/company/' . $urlicono));
                Storage::putFileAs('images/company', $this->icono, $urlicono);
                if ($this->icono->getSize() > 1048576) { //1MB
                    // $compressedImage->destroy();
                    // $compressedImage->delete();
                    $this->addError('icono', 'La imagen excede el tamaño máximo permitido.');
                }
            }

            if ($this->publickey) {
                $extpublic = FormatoPersonalizado::getExtencionFile($this->publickey->getClientOriginalName());
                $urlpublickey = 'public_' . $this->document . '.' . $extpublic;
                $this->publickey->storeAs('company/pem/', $urlpublickey, 'local');
                // Storage::disk('local')->putFileAs('company/pem/', $this->publickey, $urlpublickey);
            }

            if ($this->privatekey) {
                $extprivate = FormatoPersonalizado::getExtencionFile($this->privatekey->getClientOriginalName());
                $urlprivatekey = 'private_' . $this->document . '.' . $extprivate;
                $this->privatekey->storeAs('company/pem/', $urlprivatekey, 'local');
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
                'privatekey' => $urlprivatekey,
                'publickey' => $urlpublickey,
                'usuariosol' => $this->usuariosol,
                'clavesol' => $this->clavesol,
                'montoadelanto' => $this->montoadelanto,
                'uselistprice' => $this->uselistprice,
                'usepricedolar' => $this->usepricedolar,
                'viewpricedolar' => $this->viewpricedolar,
                'tipocambio' => $this->tipocambio,
                'tipocambioauto' => $this->tipocambioauto,
                'default' => 1,
                'igv' => $this->igv,
                'ubigeo_id' => $this->ubigeo_id,
            ]);

            $empresa->sucursals()->create([
                'name' => $this->name,
                'direccion' => $this->direccion,
                'default' => 1,
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
                    })
                    ->orientate()
                    ->encode('jpg', 30);

                $urlLogo = uniqid() . '.' . $this->logo->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/images/company/' . $urlLogo));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $compressedImage->delete();
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
        $this->resetValidation(['document', 'name', 'direccion', 'telefono', 'ubigeo_id', 'estado', 'condicion']);

        $http = new GetClient();
        $response = $http->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->name = $response->getData()->name;
                $this->direccion = $response->getData()->direccion;
                $this->estado = $response->getData()->estado;
                $this->condicion = $response->getData()->condicion;

                if (isset($response->getData()->ubigeo)) {
                    $this->ubigeo_id = Ubigeo::where('ubigeo_inei', trim($response->getData()->ubigeo))->first()->id ?? null;
                    $this->dispatchBrowserEvent('selectubigeo', $this->ubigeo_id);
                }
            } else {
                $this->addError('document', $response->getData()->message);
            }
        } else {
            $this->addError('document', 'Error al buscar cliente.');
        }
    }

    public function searchpricedolar()
    {

        $this->resetValidation(['tipocambio']);

        $http = new GetClient();
        $response = $http->getTipoCambio();

        if ($response->precioVenta) {
            $this->tipocambio = $response->precioVenta;
        }
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

    public function clearPublickey()
    {
        $this->idpublickey = rand();
        $this->reset(['publickey']);
    }

    public function clearPrivatekey()
    {
        $this->idprivatekey = rand();
        $this->reset(['privatekey']);
    }

    public function erroricono()
    {
        $this->isUploadingIcono = false;
        $this->reset(['icono']);
        $this->idicono = rand();
        $this->addError('icono', 'Error al cargar ícono');
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-select-empresa');
    }
}
