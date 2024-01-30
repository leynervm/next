<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\FormatoPersonalizado;
use App\Helpers\GetClient;
use App\Models\Empresa;
use App\Models\Image as Logo;
use App\Models\Telephone;
use App\Models\Ubigeo;
use App\Rules\ValidateFileKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

class ShowEmpresa extends Component
{

    use WithFileUploads;

    public $openphone = false;
    public $empresa, $telephone;
    public $isUploadingLogo = false;
    public $isUploadingIcono = false;
    public $isUploadingPublicKey = false;
    public $isUploadingPrivateKey = false;

    public $phone, $telefono, $iconobase64;
    public $icono, $logo;
    public $idlogo, $idicono, $idpublickey, $publickey, $idprivatekey, $privatekey;
    public $validatemail;

    protected $listeners = ['erroricono'];

    protected function rules()
    {
        return [
            'empresa.document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/'],
            'empresa.name' => ['required', 'string', 'min:3'],
            'empresa.direccion' => ['required', 'string', 'min:3'],
            'empresa.ubigeo_id' => ['required', 'integer', 'min:0', 'exists:ubigeos,id'],
            'empresa.estado' => ['required', 'string'],
            'empresa.condicion' => ['required', 'string'],
            'empresa.email' => ['nullable', 'email'],
            'empresa.web' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.igv' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'empresa.usuariosol' => ['nullable', 'string'],
            'empresa.clavesol' => ['nullable', 'string'],
            'logo' => ['nullable', 'file', 'mimes:jpg,bmp,png'],
            'icono' => ['nullable', 'file', 'mimes:ico'],
            'publickey' => ['nullable', 'file', new ValidateFileKey("pem")],
            'privatekey' => ['nullable', 'file',  new ValidateFileKey("pem")],
            'empresa.uselistprice' => ['integer', 'min:0', 'max:1'],
            'empresa.usepricedolar' => ['integer', 'min:0', 'max:1'],
            'empresa.tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'empresa.viewpricedolar' => ['integer', 'min:0', 'max:1', 'numeric', 'decimal:0,4', 'min:0'],
            'empresa.tipocambioauto' => ['integer', 'min:0', 'max:1']
        ];
    }

    public function mount(Empresa $empresa)
    {
        $this->empresa = $empresa;
        $this->telephone = new Telephone();
        $this->idlogo = rand();
        $this->idicono = rand();
        $this->idpublickey = rand();
        $this->idprivatekey = rand();
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.empresas.show-empresa', compact('ubigeos'));
    }

    public function update()
    {
        // dd($this->empresa->usepricedolar);
        $this->empresa->uselistprice = $this->empresa->uselistprice == 1 ? 1 : 0;
        $this->empresa->usepricedolar = $this->empresa->usepricedolar == true ?  1 : 0;
        $this->empresa->viewpricedolar = $this->empresa->viewpricedolar == true ?  1 : 0;
        $this->empresa->tipocambioauto = $this->empresa->tipocambioauto == true ?  1 : 0;

        if ($this->empresa->usepricedolar == 0) {
            $this->empresa->usepricedolar = 0;
            $this->empresa->viewpricedolar = 0;
            $this->empresa->tipocambioauto = 0;
            $this->empresa->tipocambio = null;
        }

        // dd($this->empresa->usepricedolar, $this->empresa->viewpricedolar, $this->empresa->tipocambioauto);

        $this->empresa->document = trim($this->empresa->document);
        $this->empresa->name = trim($this->empresa->name);
        $this->empresa->direccion = trim($this->empresa->direccion);
        $this->empresa->estado = trim($this->empresa->estado);
        $this->empresa->condicion = trim($this->empresa->condicion);
        $this->validate();

        try {
            DB::beginTransaction();

            if (!Storage::directoryExists('images/company/')) {
                Storage::makeDirectory('images/company/');
            }

            if (!Storage::directoryExists(storage_path('app/company/pem/'))) {
                Storage::disk('local')->makeDirectory('company/pem');
            }

            $urlicono = $this->empresa->icono ?? null;
            $urlpublickey = $this->empresa->publickey ?? null;
            $urlprivatekey = $this->empresa->privatekey ?? null;

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
                $urlpublickey = 'public_' . $this->empresa->document . '.' . $extpublic;
                $this->publickey->storeAs('company/pem/', $urlpublickey, 'local');
                // Storage::disk('local')->putFileAs('company/pem/', $this->publickey, $urlpublickey);
            }

            if ($this->privatekey) {
                $extprivate = FormatoPersonalizado::getExtencionFile($this->privatekey->getClientOriginalName());
                $urlprivatekey = 'private_' . $this->empresa->document . '.' . $extprivate;
                $this->privatekey->storeAs('company/pem/', $urlprivatekey, 'local');
            }


            $this->empresa->email = $this->empresa->email;
            $this->empresa->web = $this->empresa->web;
            $this->empresa->icono = $urlicono;
            $this->empresa->privatekey = $urlprivatekey;
            $this->empresa->publickey = $urlpublickey;
            $this->empresa->usuariosol = $this->empresa->usuariosol;
            $this->empresa->clavesol = $this->empresa->clavesol;
            $this->empresa->montoadelanto = $this->empresa->montoadelanto;
            $this->empresa->default = 1;
            $this->empresa->ubigeo_id = $this->empresa->ubigeo_id;
            $this->empresa->save();


            if ($this->logo) {
                if ($this->empresa->image) {
                    $this->empresa->image->deleteOrFail();
                    Storage::delete('images/company/' . $this->empresa->image->url);
                }

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

                $this->empresa->image()->create([
                    'url' => $urlLogo,
                    'default' => 1
                ]);
            }

            DB::commit();
            $this->resetValidation();
            $this->reset([
                'icono', 'logo', 'idlogo', 'idicono',
                'publickey', 'privatekey', 'idpublickey', 'idprivatekey'
            ]);
            $this->dispatchBrowserEvent('updated');
            $this->empresa->refresh();
            $this->idlogo = rand();
            $this->idicono = rand();
            $this->idpublickey = rand();
            $this->idprivatekey = rand();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodalphone()
    {
        $this->resetValidation(['telephone', 'phone']);
        $this->reset(['telephone', 'phone']);
        $this->openphone = true;
    }


    public function editphone(Telephone $telephone)
    {
        $this->resetValidation(['phone']);
        $this->telephone = $telephone;
        $this->phone = $telephone->phone;
        $this->openphone = true;
    }

    public function savetelefono()
    {

        $this->phone = trim($this->phone);
        $this->validate([
            'phone' => ['required', 'numeric', 'digits_between:6,9', 'regex:/^\d{6,9}$/']
        ]);

        if ($this->empresa) {
            $this->empresa->telephones()->updateOrCreate([
                'id' => $this->telephone->id ?? null
            ], [
                'phone' => $this->phone
            ]);

            $this->empresa->refresh();
            $this->dispatchBrowserEvent('created');
            $this->reset(['telephone', 'phone', 'openphone']);
        }
    }

    public function deletephone(Telephone $telephone)
    {
        $telephone->deleteOrFail();
        $this->empresa->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function deletepublickey(Empresa $empresa)
    {
        if ($empresa) {
            // dd(Storage::disk('local')->exists('company/pem/' . $this->empresa->publickey));
            if (Storage::disk('local')->exists('company/pem/' . $this->empresa->publickey)) {
                Storage::disk('local')->delete('company/pem/' . $this->empresa->publickey);
            }

            $empresa->publickey = null;
            $empresa->save();
            $this->idpublickey = rand();
            $this->empresa->refresh();
        }
    }

    public function deleteprivatekey(Empresa $empresa)
    {
        if ($empresa) {
            if (Storage::disk('local')->exists('company/pem/' . $empresa->privatekey)) {
                Storage::disk('local')->delete('company/pem/' . $empresa->privatekey);
            }

            $empresa->privatekey = null;
            $empresa->save();
            $this->empresa->refresh();
            $this->idprivatekey = rand();
        }
    }

    public function deleteicono(Empresa $empresa)
    {
        if ($empresa) {
            if (Storage::exists('images/company/' . $empresa->icono)) {
                Storage::delete('images/company/' . $empresa->icono);
            }

            $empresa->icono = null;
            $empresa->save();
            $this->empresa->refresh();
            $this->idicono = rand();
        }
    }

    public function deletelogo(Logo $logo)
    {
        if ($logo) {
            if (Storage::exists('images/company/' . $logo->url)) {
                Storage::delete('images/company/' . $logo->url);
            }

            $logo->deleteOrFail();
            $this->empresa->refresh();
            $this->idlogo = rand();
        }
    }

    public function searchclient()
    {

        $this->empresa->document = trim($this->empresa->document);
        $validate = $this->validate([
            'empresa.document' => 'required|numeric|digits:11|regex:/^\d{11}$/'
        ]);

        $this->empresa->name = null;
        $this->empresa->direccion = null;
        $this->empresa->telefono = null;
        $this->empresa->ubigeo_id = null;
        $this->empresa->estado = null;
        $this->empresa->condicion = null;
        $this->resetValidation(['empresa.document', 'empresa.name', 'empresa.direccion', 'empresa.telefono', 'empresa.ubigeo_id', 'empresa.estado', 'condicion']);

        $http = new GetClient();
        $response = $http->getClient($this->empresa->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->empresa->name = $response->getData()->name;
                $this->empresa->direccion = $response->getData()->direccion;
                $this->empresa->estado = $response->getData()->estado;
                $this->empresa->condicion = $response->getData()->condicion;

                if (isset($response->getData()->ubigeo)) {
                    $this->empresa->ubigeo_id = Ubigeo::where('ubigeo_inei', trim($response->getData()->ubigeo))->first()->id ?? null;
                }
            } else {
                $this->addError('empresa.document', $response->getData()->message);
            }
        } else {
            $this->addError('empresa.document', 'Error al buscar cliente.');
        }
    }

    // public function searchpricedolar()
    // {

    //     $this->resetValidation(['empresa.tipocambio']);

    //     $http = new GetClient();
    //     $response = $http->getTipoCambio();

    //     if ($response->precioVenta) {
    //         $this->empresa->tipocambio = $response->precioVenta;
    //     }
    // }

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

    // public function updatedIcono()
    // {
    //     $this->isUploadingIcono = true;
    //     $this->dispatchBrowserEvent('iconloaded');
    // }

    // public function iconloaded()
    // {
    //     $this->isUploadingIcono = false;
    // }
}
