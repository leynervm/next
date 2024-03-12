<?php

namespace App\Http\Livewire\Admin\Proveedores;

use App\Helpers\GetClient;
use App\Models\Proveedor;
use App\Models\Proveedortype;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateProveedor extends Component
{

    use AuthorizesRequests;

    public $document, $name, $direccion, $ubigeo_id,
        $proveedortype_id, $telefono, $email, $document2, $name2, $telefono2;


    protected function rules()
    {
        return [
            'document' => ['required', 'numeric', 'digits:11', 'regex:/^\d{11}$/', new CampoUnique('proveedors', 'document', null, true)],
            'name' => ['required', 'string', 'min:3'],
            'direccion' => ['required', 'string', 'min:3'],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'proveedortype_id' => ['required', 'integer', 'min:1', 'exists:proveedortypes,id'],
            'telefono' => ['required', 'numeric', 'digits_between:7,9'],
            'email' => ['nullable', 'email', 'min:6'],
            'document2' => ['required', 'numeric', 'digits:8', 'regex:/^\d{8}$/'],
            'name2' => ['required', 'string', 'min:3'],
            'telefono2' => ['required', 'numeric', 'digits_between:7,9'],
        ];
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $proveedortypes = Proveedortype::orderBy('name', 'asc')->get();
        return view('livewire.admin.proveedores.create-proveedor', compact('ubigeos', 'proveedortypes'));
    }

    public function save()
    {

        $this->authorize('admin.proveedores.create');
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->telefono = trim($this->telefono);
        $this->email = trim($this->email);
        $this->document2 = trim($this->document2);
        $this->name2 = trim($this->name2);
        $this->telefono2 = trim($this->telefono2);

        $validateData = $this->validate();
        try {

            DB::beginTransaction();
            $proveedor = Proveedor::create($validateData);

            $proveedor->telephones()->create([
                'phone' => $this->telefono
            ]);

            $representante = $proveedor->contacts()->create([
                'document' => $this->document2,
                'name' => $this->name2
            ]);

            $representante->telephone()->create([
                'phone' => $this->telefono2
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('created');
            return redirect()->route('admin.proveedores');
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

        $this->authorize('admin.proveedores.create');
        $this->document = trim($this->document);
        $this->validate([
            'document' => ['required', 'numeric', new ValidateDocument]
        ]);

        $this->name = null;
        $this->direccion = null;
        $this->telefono = null;
        $this->ubigeo_id = null;

        $this->resetValidation(['document', 'name', 'direccion', 'telefono', 'ubigeo_id']);

        $http = new GetClient();
        $response = $http->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->name = $response->getData()->name;
                $this->direccion = $response->getData()->direccion;
                $this->telefono = $response->getData()->telefono;

                if ($response->getData()->ubigeo) {
                    $this->ubigeo_id = Ubigeo::where('ubigeo_inei', trim($response->getData()->ubigeo))->first()->id ?? null;
                }
            } else {
                $this->addError('document', $response->getData()->message);
            }
        } else {
            $this->addError('document', 'Error al buscar cliente.');
        }
    }

    public function searchcontacto()
    {

        $this->authorize('admin.proveedores.create');
        $this->document2 = trim($this->document2);
        $this->validate([
            'document2' => ['required', 'numeric', 'digits:8']
        ]);

        $this->name2 = null;
        $this->telefono2 = null;
        $this->resetValidation(['document2', 'name2', 'telefono2']);

        $http = new GetClient();
        $response = $http->getClient($this->document2);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->name2 = $response->getData()->name;
                $this->telefono2 = $response->getData()->telefono;
            } else {
                $this->addError('document2', $response->getData()->message);
            }
        } else {
            $this->addError('document2', 'Error al buscar datos del representante.');
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-select2-proveedores');
    }
}
