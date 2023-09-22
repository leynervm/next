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

    public $empresa, $telephone;

    public $isUploadingLogo = false;
    public $isUploadingIcono = false;
    public $isUploadingPublicKey = false;
    public $isUploadingPrivateKey = false;

    public $openphone = false;
    public $phone;

    public $icono;
    public $logo;
    public $idlogo, $idicono, $idpublickey, $publickey, $idprivatekey, $privatekey;
    public $telefono;

    public $iconobase64;

    protected $listeners = ['erroricono', 'iconloaded'];


    protected function rules()
    {
        return [
            'empresa.document' => ['required', 'numeric', 'digits:11'],
            'empresa.name' => ['required'],
            'empresa.direccion' => ['required'],
            'empresa.ubigeo_id' => ['required', 'integer', 'min:0', 'exists:ubigeos,id'],
            'empresa.estado' => ['required', 'string'],
            'empresa.condicion' => ['required', 'string'],
            'empresa.email' => ['nullable'],
            'empresa.web' => ['nullable'],
            'empresa.telefono' => ['nullable', 'numeric', 'digits_between:9,12'],
            'empresa.montoadelanto' => ['nullable', 'decimal:0,2'],
            'empresa.usuariosol' => ['nullable'],
            'empresa.clavesol' => ['nullable'],
            'empresa.logo' => ['nullable', 'file', 'mimes:jpg,bmp,png'],
            'icono' => ['nullable', 'file', 'mimes:ico'],
            'publickey' => ['nullable', 'file', new ValidateFileKey("pem")],
            'privatekey' => ['nullable', 'file',  new ValidateFileKey("pem")],
            'empresa.validatemail' => ['integer', 'min:0', 'max:1'],
            'empresa.dominiocorreo' => ['nullable', 'required_if:validatemail,1'],
            'empresa.uselistprice' => ['integer', 'min:0', 'max:1'],
            'empresa.usepricedolar' => ['integer', 'min:0', 'max:1'],
            'empresa.tipocambio' => ['nullable', 'required_if:usepricedolar,1'],
            'empresa.viewpricedolar' => ['integer', 'min:0', 'max:1'],
            'empresa.tipocambioauto' => ['integer', 'min:0', 'max:1']
        ];
    }

    public function mount()
    {
        $this->empresa = new Empresa();
        $this->telephone = new Telephone();
        $this->empresa = Empresa::first() ?? new Empresa();
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

    public function save()
    {

        $this->empresa->validatemail = $this->empresa->validatemail == 1 ?  1 : 0;
        $this->empresa->uselistprice = $this->empresa->uselistprice == 1 ?  1 : 0;
        $this->empresa->usepricedolar = $this->empresa->usepricedolar == 1 ?  1 : 0;
        $this->empresa->viewpricedolar = $this->empresa->viewpricedolar == 1 ?  1 : 0;
        $this->empresa->tipocambioauto = $this->empresa->tipocambioauto == 1 ?  1 : 0;
        $this->empresa->document = trim($this->empresa->document);
        $this->empresa->name = trim($this->empresa->name);
        $this->empresa->direccion = trim($this->empresa->direccion);
        $this->empresa->estado = trim($this->empresa->estado);
        $this->validate();
        $event = isset($this->empresa->id) ? 'updated' : 'created';

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

            $empresa = Empresa::updateOrCreate(
                ['document' => $this->empresa->document],
                [
                    'name' => $this->empresa->name,
                    'estado' => $this->empresa->estado,
                    'condicion' => $this->empresa->condicion,
                    'direccion' => $this->empresa->direccion,
                    'email' => $this->empresa->email,
                    'web' => $this->empresa->web,
                    'icono' => $urlicono,
                    'privatekey' => $urlprivatekey,
                    'publickey' => $urlpublickey,
                    'usuariosol' => $this->empresa->usuariosol,
                    'clavesol' => $this->empresa->clavesol,
                    'montoadelanto' => $this->empresa->montoadelanto,
                    'uselistprice' => $this->empresa->uselistprice,
                    'usepricedolar' => $this->empresa->usepricedolar,
                    'viewpricedolar' => $this->empresa->viewpricedolar,
                    'tipocambio' => $this->empresa->tipocambio,
                    'tipocambioauto' => $this->empresa->tipocambioauto,
                    'default' => 1,
                    'ubigeo_id' => $this->empresa->ubigeo_id,
                ]
            );

            if ($this->empresa->telefono) {
                $empresa->telephones()->create([
                    'phone' => trim($this->empresa->telefono)
                ]);
            }


            if ($this->logo) {
                if ($empresa->image) {
                    $empresa->image->deleteOrFail();
                    Storage::delete('images/company/' . $empresa->image->url);
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

                $empresa->image()->create([
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

            $this->dispatchBrowserEvent($event);
            return redirect()->route('admin.administracion.empresa');
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

        $validate = $this->validate([
            'phone' => ['required', 'numeric', 'digits_between:7,9']
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
        }
    }


    public function searchclient()
    {

        $this->empresa->document = trim($this->empresa->document);
        $validate = $this->validate([
            'empresa.document' => 'required|numeric|digits:11'
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

    public function searchpricedolar()
    {

        $this->resetValidation(['empresa.tipocambio']);

        $http = new GetClient();
        $response = $http->getTipoCambio();

        if ($response->precioVenta) {
            $this->empresa->tipocambio = $response->precioVenta;
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

    // public function updatedIcono()
    // {
    //     $this->isUploadingIcono = true;
    //     $this->dispatchBrowserEvent('iconloaded');
    // }

    // public function iconloaded()
    // {
    //     $this->isUploadingIcono = false;
    // }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-select-empresa');
        // $this->emit('iconload');
    }
}
