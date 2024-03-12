<?php

namespace App\Http\Livewire\Admin\Proveedores;

use App\Helpers\GetClient;
use App\Models\Contact;
use App\Models\Proveedor;
use App\Models\Proveedortype;
use App\Models\Telephone;
use App\Models\Ubigeo;
use App\Rules\ValidateDocument;
use App\Rules\ValidatePhoneClient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShowProveedor extends Component
{

    use AuthorizesRequests;

    public $proveedor, $contact, $telephone;
    public $document2, $name2, $telefono2, $newtelefono;
    public $openrepresentante = false;
    public $openphone = false;

    protected function rules()
    {
        return [
            'proveedor.document' => ['required', 'numeric', 'digits:11', 'unique:proveedors,document,' . $this->proveedor->id],
            'proveedor.name' => ['required', 'string', 'min:3'],
            'proveedor.direccion' => ['required', 'string', 'min:3'],
            'proveedor.ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'proveedor.proveedortype_id' => ['required', 'integer', 'min:1', 'exists:proveedortypes,id'],
            'proveedor.email' => ['nullable', 'email', 'min:6'],
        ];
    }

    public function mount(Proveedor $proveedor)
    {
        $this->proveedor = $proveedor;
        $this->telephone = new Telephone();
        $this->contact = new Contact();
    }

    public function render()
    {
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $proveedortypes = Proveedortype::orderBy('name', 'asc')->get();
        return view('livewire.admin.proveedores.show-proveedor', compact('ubigeos', 'proveedortypes'));
    }

    public function update()
    {
        $this->authorize('admin.proveedores.edit');
        $this->proveedor->document = trim($this->proveedor->document);
        $this->proveedor->name = trim($this->proveedor->name);
        $this->proveedor->direccion = trim($this->proveedor->direccion);
        $this->proveedor->email = trim($this->proveedor->email);

        $this->validate();
        $this->proveedor->save();
        $this->resetValidation();
        $this->dispatchBrowserEvent('updated');
    }


    public function delete(Proveedor $proveedor)
    {

        $this->authorize('admin.proveedores.delete');
        if ($proveedor->compras()->exists()) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar proveedor ' . $proveedor->name,
                'text' => "El proveedor contiene compras vinculadas, eliminar causará conflictos en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            $proveedor->contacts()->delete();
            $proveedor->telephones()->delete();
            $proveedor->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
            return redirect()->route('admin.proveedores');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodalrepresentante()
    {
        $this->authorize('admin.proveedores.contacts.edit');
        $this->reset(['contact', 'document2', 'name2', 'telefono2']);
        $this->resetValidation();
        $this->openrepresentante = true;
    }

    public function editrepresentante(Contact $contact)
    {
        $this->authorize('admin.proveedores.contacts.edit');
        $this->contact = $contact;
        $this->reset(['document2', 'name2', 'telefono2']);
        $this->resetValidation();
        $this->document2 = trim($contact->document);
        $this->name2 = trim($contact->name);
        if ($contact->telephone) {
            $this->telefono2 = trim($contact->telephone->phone);
        }
        $this->openrepresentante = true;
    }

    public function saverepresentante()
    {

        $this->authorize('admin.proveedores.contacts.edit');
        $this->document2 = trim($this->document2);
        $this->name2 = trim($this->name2);
        $this->validate([
            'document2' => ['required', 'numeric', 'digits:8'],
            'name2' => ['required', 'string', 'min:3'],
            'telefono2' => ['required', 'numeric'],
        ]);

        try {

            DB::beginTransaction();
            $representante = $this->proveedor->contacts()->updateOrCreate([
                'id' => $this->contact->id ?? null
            ], [
                'document' => $this->document2,
                'name' => $this->name2
            ]);

            $representante->telephone()->updateOrCreate([
                'id' => $this->contact->telephone->id ?? null
            ], [
                'phone' => $this->telefono2
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->reset(['openrepresentante', 'document2', 'name2', 'telefono2', 'contact']);
            $this->resetValidation();
            $this->proveedor->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function editphone(Telephone $telephone)
    {
        $this->authorize('admin.proveedores.phones.edit');
        $this->reset(['newtelefono']);
        $this->resetValidation(['telephone', 'newtelefono']);
        $this->telephone = $telephone;
        $this->newtelefono = trim($telephone->phone);
        $this->openphone = true;
    }

    public function openmodalphone()
    {
        $this->authorize('admin.proveedores.phones.edit');
        $this->reset(['newtelefono']);
        $this->resetValidation(['telephone', 'newtelefono']);
        $this->openphone = true;
    }

    public function savephone()
    {

        $this->authorize('admin.proveedores.phones.edit');
        $this->telefono2 = trim($this->telefono2);
        $this->validate([
            'newtelefono' => [
                'required', 'numeric', 'digits_between:7,9',
                new ValidatePhoneClient('proveedors', $this->proveedor->id, $this->telephone->id ?? null)
            ]
        ]);
        try {

            DB::beginTransaction();
            $this->proveedor->telephones()->updateOrCreate([
                'id' => $this->telephone->id ?? null
            ], [
                'phone' => $this->newtelefono
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->reset(['openphone', 'newtelefono', 'telephone']);
            $this->resetValidation(['telephone', 'newtelefono']);
            $this->proveedor->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletephone(Telephone $telephone)
    {
        $this->authorize('admin.proveedores.phones.edit');
        $telephone->delete();
        $this->proveedor->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function deleterepresentante(Contact $contact)
    {
        $this->authorize('admin.proveedores.contacts.edit');
        $contact->telephone->delete();
        $contact->delete();
        $this->proveedor->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function searchclient()
    {

        $this->authorize('admin.proveedores.edit');
        $this->proveedor->document = trim($this->proveedor->document);
        $this->validate([
            'proveedor.document' => ['required', 'numeric', new ValidateDocument]
        ]);

        $this->proveedor->name = null;
        $this->proveedor->direccion = null;
        $this->proveedor->telefono = null;
        $this->proveedor->ubigeo_id = null;

        $this->resetValidation(['proveedor.document', 'proveedor.name', 'proveedor.direccion', 'telefono', 'proveedor.proveedor->ubigeo_id']);

        $http = new GetClient();
        $response = $http->getClient($this->proveedor->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->proveedor->name = $response->getData()->name;
                $this->proveedor->direccion = $response->getData()->direccion;
                $this->proveedor->telefono = $response->getData()->telefono;

                if ($response->getData()->ubigeo) {
                    $this->proveedor->ubigeo_id = Ubigeo::where('ubigeo_inei', trim($response->getData()->ubigeo))->first()->id ?? null;
                }
            } else {
                $this->addError('proveedor.document', $response->getData()->message);
            }
        } else {
            $this->addError('proveedor.document', 'Error al buscar cliente.');
        }
    }

    public function searchcontacto()
    {

        $this->authorize('admin.proveedores.contacts.edit');
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
}
