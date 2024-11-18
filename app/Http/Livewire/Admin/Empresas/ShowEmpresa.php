<?php

namespace App\Http\Livewire\Admin\Empresas;

use App\Helpers\FormatoPersonalizado;
use App\Helpers\GetClient;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Telephone;
use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

class ShowEmpresa extends Component
{

    use WithFileUploads;

    public $openphone = false;
    public $empresa, $telephone, $phone, $telefono;
    public $icono, $logo, $idlogo, $idicono, $usemarkagua, $mark, $logofooter,
        $extencionlogofooter, $logoimpresion, $extencionlogoimpresion;
    public $validatemail;

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
            'empresa.whatsapp' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.facebook' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.youtube' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.instagram' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.tiktok' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.igv' => ['required', 'numeric', 'decimal:0,2', 'min:0'],
            'logo' => ['nullable', 'file', 'mimes:jpg,bmp,png'],
            'icono' => ['nullable', 'file', 'mimes:ico'],
            'mark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua() && empty($this->empresa->markagua)), 'file', 'mimes:png'],
            'empresa.uselistprice' => ['integer', 'min:0', 'max:1'],
            'empresa.viewpriceantes' => ['integer', 'min:0', 'max:1'],
            'empresa.viewlogomarca' => ['integer', 'min:0', 'max:1'],
            'empresa.viewtextopromocion' => ['integer', 'min:0', 'max:2'],
            'empresa.usemarkagua' => ['integer', 'min:0', 'max:1'],
            'empresa.viewespecificaciones' => ['integer', 'min:0', 'max:1'],
            'empresa.generatesku' => ['integer', 'min:0', 'max:1'],
            'empresa.alignmark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua()), 'string', 'max:25'],
            'empresa.widthmark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua()), 'integer', 'min:50', 'max:300'],
            'empresa.heightmark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua()), 'integer', 'min:50', 'max:300'],
            'empresa.usepricedolar' => ['integer', 'min:0', 'max:1'],
            'empresa.viewpricedolar' => ['integer', 'min:0', 'max:1'],
            'empresa.viewproductosweb' => ['integer', 'min:0', 'max:1'],
            'empresa.viewalmacens' => ['integer', 'min:0', 'max:1'],
            'empresa.viewalmacensdetalle' => ['integer', 'min:0', 'max:1'],
            'empresa.textnovedad' => ['nullable', 'string', 'max:50'],
            'empresa.tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'empresa.tipocambioauto' => ['integer', 'min:0', 'max:1'],
            'empresa.montoadelanto' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
        ];
    }

    public function mount(Empresa $empresa)
    {
        $this->empresa = $empresa;
        $this->telephone = new Telephone();
        $this->idlogo = rand();
        $this->idicono = rand();
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.empresas.show-empresa', compact('ubigeos'));
    }

    public function update()
    {

        $this->empresa->document = trim($this->empresa->document);
        $this->empresa->name = trim($this->empresa->name);
        $this->empresa->direccion = trim($this->empresa->direccion);
        $this->empresa->estado = trim($this->empresa->estado);
        $this->empresa->condicion = trim($this->empresa->condicion);
        $this->validate([
            'empresa.document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/'],
            'empresa.name' => ['required', 'string', 'min:3'],
            'empresa.direccion' => ['required', 'string', 'min:3'],
            'empresa.ubigeo_id' => ['required', 'integer', 'min:0', 'exists:ubigeos,id'],
            'empresa.estado' => ['required', 'string'],
            'empresa.condicion' => ['required', 'string'],
            'empresa.email' => ['nullable', 'email'],
            'empresa.web' => ['nullable', 'starts_with:http://,https://,https://www.,http://www.,www.'],
            'empresa.igv' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
        ]);
        try {
            DB::beginTransaction();
            // if ($this->publickey) {
            //     $extpublic = FormatoPersonalizado::getExtencionFile($this->publickey->getClientOriginalName());
            //     $urlpublickey = 'public_' . $this->empresa->document . '.' . $extpublic;
            //     $this->publickey->storeAs('company/pem/', $urlpublickey, 'local');
            //     // Storage::disk('local')->putFileAs('company/pem/', $this->publickey, $urlpublickey);
            // }

            $this->empresa->email = $this->empresa->email;
            $this->empresa->web = $this->empresa->web;
            $this->empresa->default = 1;
            $this->empresa->ubigeo_id = $this->empresa->ubigeo_id;
            $this->empresa->save();
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('updated');
            $this->empresa->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateopciones()
    {
        $this->empresa->montoadelanto = empty($this->empresa->montoadelanto) ? null : $this->empresa->montoadelanto;
        $this->empresa->uselistprice = $this->empresa->uselistprice == 1 ? 1 : 0;
        $this->empresa->usepricedolar = $this->empresa->usepricedolar == true ?  1 : 0;
        $this->empresa->viewpricedolar = $this->empresa->viewpricedolar == true ?  1 : 0;
        $this->empresa->tipocambioauto = $this->empresa->tipocambioauto == true ?  1 : 0;
        $this->empresa->viewpriceantes = $this->empresa->viewpriceantes == true ?  1 : 0;
        $this->empresa->viewlogomarca = $this->empresa->viewlogomarca == true ?  1 : 0;
        $this->empresa->usemarkagua = $this->empresa->usemarkagua == true ?  1 : 0;
        $this->empresa->generatesku = $this->empresa->generatesku == true ?  1 : 0;
        $this->empresa->viewalmacens = $this->empresa->viewalmacens == true ?  1 : 0;
        $this->empresa->viewalmacensdetalle = $this->empresa->viewalmacensdetalle == true ?  1 : 0;
        $this->empresa->textnovedad = trim($this->empresa->textnovedad);

        if ($this->empresa->usepricedolar == 0) {
            $this->empresa->usepricedolar = 0;
            $this->empresa->viewpricedolar = 0;
            $this->empresa->tipocambioauto = 0;
            $this->empresa->tipocambio = null;
        }

        $this->validate([
            'logo' => ['nullable', 'file', 'mimes:jpg,bmp,png'],
            'icono' => ['nullable', 'file', 'mimes:ico'],
            'mark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua() && empty($this->empresa->markagua)), 'file', 'mimes:png'],
            'empresa.uselistprice' => ['integer', 'min:0', 'max:1'],
            'empresa.viewpriceantes' => ['integer', 'min:0', 'max:1'],
            'empresa.viewlogomarca' => ['integer', 'min:0', 'max:1'],
            'empresa.viewtextopromocion' => ['integer', 'min:0', 'max:2'],
            'empresa.usemarkagua' => ['integer', 'min:0', 'max:1'],
            'empresa.alignmark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua()), 'string', 'max:25'],
            'empresa.widthmark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua()), 'integer', 'min:50', 'max:300'],
            'empresa.heightmark' => ['nullable', Rule::requiredIf($this->empresa->usarMarkagua()), 'integer', 'min:50', 'max:300'],
            'empresa.usepricedolar' => ['integer', 'min:0', 'max:1'],
            'empresa.tipocambio' => ['nullable', 'required_if:usepricedolar,1', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'empresa.viewpricedolar' => ['integer', 'min:0', 'max:1', 'numeric', 'decimal:0,4', 'min:0'],
            'empresa.tipocambioauto' => ['integer', 'min:0', 'max:1'],
            'empresa.montoadelanto' => ['nullable', 'numeric', 'min:0', 'decimal:0,2'],
            'empresa.generatesku' => ['integer', 'min:0', 'max:1'],
            'empresa.textnovedad' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            DB::beginTransaction();

            if ($this->mark) {
                if (!Storage::directoryExists('images/company/')) {
                    Storage::makeDirectory('images/company/');
                }

                $compressedImage = Image::make($this->mark->getRealPath())
                    ->orientate()->encode('jpg', 50);

                $markURL = uniqid('markagua_') . '.' . $this->mark->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/images/company/' . $markURL));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('mark', 'La imagen excede el tamaño máximo permitido.');
                    return false;
                }

                if ($this->empresa->markagua) {
                    Storage::delete($this->empresa->getMarkAguaURL());
                }

                $this->empresa->markagua = $markURL;
            }

            $this->empresa->save();
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('updated');
            $this->empresa->refresh();
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

    public function syncskuproductos()
    {
        if (view()->shared('empresa')->autogenerateSku()) {
            $productos = Producto::select('id', 'sku')
                ->whereNull('sku')->orWhere('sku', '')->get()->each(function ($item) {

                    $sku = str_pad((int)$item->id, 6, '0', STR_PAD_LEFT);
                    $existsku = DB::table('productos')->where('sku', $sku)->whereNot('id', $item->id)->exists();
                    if ($existsku) {
                        $sku = DB::table('productos')->max('id');
                        do {
                            $sku = str_pad((int)$sku + 1, 6, '0', STR_PAD_LEFT);
                        } while (DB::table('productos')->where('sku', $sku)->exists());
                    }
                    $item->sku = $sku;
                    $item->save();
                });
            // dd($productos);
            $this->dispatchBrowserEvent('updated');
        } else {
            $mensaje = response()->json([
                'title' => "PRIMERO ACTUALIZAR CAMBIOS CON LA OPCION DE AUTOGENERAR SKU ACTIVADA",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
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
        $response = $http->getSunat($this->empresa->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->empresa->name = $response->getData()->name;
                $this->empresa->direccion = $response->getData()->direccion;
                $this->empresa->estado = $response->getData()->estado;
                $this->empresa->condicion = $response->getData()->condicion;
                $this->empresa->ubigeo_id = trim($response->getData()->ubigeo_id);
            } else {
                $this->addError('empresa.document', $response->getData()->message);
            }
        } else {
            $this->addError('empresa.document', 'Error al buscar cliente.');
        }
    }

    public function savelogo()
    {

        $this->validate(['logo' => ['nullable', 'file', 'mimes:jpg,bmp,png']]);
        if (!Storage::directoryExists('images/company/')) {
            Storage::makeDirectory('images/company/');
        }

        if ($this->logo) {
            $compressedImage = Image::make($this->logo->getRealPath())
                ->orientate()->encode('jpg', 50);

            $urlLogo = 'logo_' . uniqid() . '.' . $this->logo->getClientOriginalExtension();
            $compressedImage->save(public_path('storage/images/company/' . $urlLogo));

            if ($compressedImage->filesize() > 1048576) { //1MB
                $compressedImage->destroy();
                $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
                return false;
            }


            if ($this->empresa->image) {
                if (Storage::exists('images/company/' . $this->empresa->image->url)) {
                    Storage::delete('images/company/' . $this->empresa->image->url);
                    $this->empresa->image->delete();
                }
            }

            $this->empresa->image()->create([
                'url' => $urlLogo,
                'default' => 1
            ]);

            $this->reset(['logo', 'idlogo']);
            $this->resetValidation();
            $this->idlogo = rand();
            $this->empresa->refresh();
            $this->dispatchBrowserEvent('created');
        }
    }

    public function saveicono()
    {
        $this->validate(['icono' => ['nullable', 'file', 'mimes:ico']]);
        if (!Storage::directoryExists('images/company/')) {
            Storage::makeDirectory('images/company/');
        }

        $urlicono = $this->empresa->icono ?? null;
        if ($this->icono) {
            $extpublic = FormatoPersonalizado::getExtencionFile($this->icono->getClientOriginalName());
            $urlicono = 'icon_' . uniqid() . '.' . $extpublic;
            Storage::putFileAs('images/company', $this->icono, $urlicono);
            if ($this->icono->getSize() > 1048576) { //1MB
                $this->addError('icono', 'La imagen excede el tamaño máximo permitido.');
                return false;
            }

            if ($this->empresa->icono) {
                if (Storage::exists('images/company/' . $this->empresa->icono)) {
                    Storage::delete('images/company/' . $this->empresa->icono);
                }
            }

            $this->empresa->icono = $urlicono;
            $this->empresa->save();
            $this->reset(['icono', 'idicono']);
            $this->resetValidation();
            $this->idicono = rand();
            $this->dispatchBrowserEvent('created');
        }
    }

    public function savelogofooter()
    {
        $this->validate(['logofooter' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg);base64,([A-Za-z0-9+\/=]+)$/']]);
        if (!Storage::directoryExists('images/company/')) {
            Storage::makeDirectory('images/company/');
        }

        $urlfooterlogo = $this->empresa->logofooter ?? null;
        if ($this->logofooter) {
            $imageFooter = $this->logofooter;
            list($type, $imageFooter) = explode(';', $imageFooter);
            list(, $imageFooter) = explode(',', $imageFooter);
            $imageFooter = base64_decode($imageFooter);

            if (!Storage::directoryExists('images/company/')) {
                Storage::makeDirectory('images/company/');
            }

            $compressedFooter = Image::make($imageFooter)->orientate()->encode('jpg', 70);
            if ($compressedFooter->filesize() > 1048576) { //1MB
                $compressedFooter->destroy();
                $this->addError('logofooter', 'La imagen excede el tamaño máximo permitido.');
                return false;
            }
            $urlfooterlogo = uniqid('footer_') . '.' . $this->extencionlogofooter;
            $compressedFooter->save(public_path('storage/images/company/' . $urlfooterlogo));

            if ($this->empresa->logofooter) {
                if (Storage::exists('images/company/' . $this->empresa->logofooter)) {
                    Storage::delete('images/company/' . $this->empresa->logofooter);
                }
            }

            $this->empresa->logofooter = $urlfooterlogo;
            $this->empresa->save();
            $this->reset(['logofooter', 'extencionlogofooter']);
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
        }
    }

    public function savelogoimpresion()
    {
        $this->validate([
            'logoimpresion' =>
            ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg);base64,([A-Za-z0-9+\/=]+)$/'],
            'extencionlogoimpresion' =>
            ['nullable', 'string', 'in:jpg,png,jpeg']
        ]);
        if (!Storage::directoryExists('images/company/')) {
            Storage::makeDirectory('images/company/');
        }

        $urlimpresionlogo = $this->empresa->logoimpresion ?? null;
        if ($this->logoimpresion) {
            $imageImpresion = $this->logoimpresion;
            list($type, $imageImpresion) = explode(';', $imageImpresion);
            list(, $imageImpresion) = explode(',', $imageImpresion);
            $imageImpresion = base64_decode($imageImpresion);

            if (!Storage::directoryExists('images/company/')) {
                Storage::makeDirectory('images/company/');
            }

            $compressedPrint = Image::make($imageImpresion)->orientate()->encode('jpg', 70);
            if ($compressedPrint->filesize() > 1048576) { //1MB
                $compressedPrint->destroy();
                $this->addError('logoimpresion', 'La imagen excede el tamaño máximo permitido.');
                return false;
            }
            $urlimpresionlogo = uniqid('logoprint_') . '.' . $this->extencionlogoimpresion;
            $compressedPrint->save(public_path('storage/images/company/' . $urlimpresionlogo));

            if ($this->empresa->logoimpresion) {
                if (Storage::exists('images/company/' . $this->empresa->logoimpresion)) {
                    Storage::delete('images/company/' . $this->empresa->logoimpresion);
                }
            }

            $this->empresa->logoimpresion = $urlimpresionlogo;
            $this->empresa->save();
            $this->reset(['logoimpresion', 'extencionlogoimpresion']);
            $this->resetValidation();
            $this->dispatchBrowserEvent('created');
        }
    }

    public function deleteicono()
    {
        if ($this->empresa->icono) {
            if (Storage::exists('images/company/' . $this->empresa->icono)) {
                Storage::delete('images/company/' . $this->empresa->icono);
            }
            $this->empresa->icono = null;
            $this->empresa->save();
            $this->empresa->refresh();
            $this->idicono = rand();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function deletelogo()
    {
        if ($this->empresa->image) {
            if (Storage::exists('images/company/' . $this->empresa->image->url)) {
                Storage::delete('images/company/' . $this->empresa->image->url);
            }
            $this->empresa->image->delete();
            $this->empresa->refresh();
            $this->idlogo = rand();
            $this->dispatchBrowserEvent('deleted');
        }
    }

    public function deletelogofooter()
    {
        if ($this->empresa->logofooter) {
            if (Storage::exists('images/company/' . $this->empresa->logofooter)) {
                Storage::delete('images/company/' . $this->empresa->logofooter);
            }
            $this->empresa->logofooter = null;
            $this->reset(['logofooter', 'extencionlogofooter']);
            $this->empresa->save();
            $this->empresa->refresh();
            $this->dispatchBrowserEvent('deleted');
            return true;
        }
    }

    public function deletelogoimpresion()
    {
        if ($this->empresa->logoimpresion) {
            if (Storage::exists('images/company/' . $this->empresa->logoimpresion)) {
                Storage::delete('images/company/' . $this->empresa->logoimpresion);
            }

            $this->empresa->logoimpresion = null;
            $this->reset(['logoimpresion', 'extencionlogoimpresion']);
            $this->empresa->save();
            $this->empresa->refresh();
            $this->dispatchBrowserEvent('deleted');
            return true;
        }
    }

    public function clearLogo()
    {
        $this->reset(['logo']);
        $this->idlogo = rand();
        $this->resetValidation();
    }

    public function clearIcono()
    {
        $this->reset(['icono']);
        $this->idicono = rand();
        $this->resetValidation();
    }

    public function updatedLogo($file)
    {
        try {
            $url = $file->temporaryUrl();
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->reset(['logo']);
            $this->addError('logo', __($e->getMessage()));
            return;
        }
    }

    public function updatedMark($file)
    {
        try {
            $url = $file->temporaryUrl();
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->reset(['mark']);
            $this->addError('mark', $e->getMessage());
            return;
        }
    }

    public function clearMark()
    {
        $this->reset(['mark']);
        $this->resetValidation();
    }
}
