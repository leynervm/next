<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Helpers\GetClient;
use App\Models\Channelsale;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Direccion;
use App\Models\Pricetype;
use App\Models\Telephone;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateContactClient;
use App\Rules\ValidateDireccion;
use App\Rules\ValidateDocument;
use App\Rules\ValidateNacimiento;
use App\Rules\ValidatePhoneClient;
use App\Rules\ValidatePhoneContact;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewClient extends Component
{

    public $client, $contact;
    public $telephone, $direccion;

    public $opencontacto = false;
    public $openphone = false;
    public $document2, $name2, $telefono2, $newtelefono;

    protected $listeners = ['delete', 'deletecontacto', 'deletephone'];


    protected function rules()
    {
        return [
            'client.document' => [
                'required', 'integer', 'numeric', 'digits_between:8,11', new ValidateDocument,
                new CampoUnique('clients', 'document', $this->client->id, true)
            ],
            'client.name' => ['required', 'min:3'],
            'client.email' => ['nullable', 'min:6', 'email'],
            'client.sexo' => ['nullable', 'string', 'min:1'],
            'client.nacimiento' => ['nullable', new ValidateNacimiento()],
            'client.pricetype_id' => ['required', 'integer', 'min:1', 'exists:pricetypes,id'],
            'direccion.name' => ['required', 'string', 'min:3'],
            'direccion.ubigeo_id' => ['nullable', 'integer', 'min:1', 'exists:ubigeos,id'],
        ];
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->contact = new Contact();
        $this->direccion = $client->direccion;
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $channelsales = Channelsale::orderBy('name', 'asc')->get();
        return view('livewire.admin.clients.view-client', compact('pricetypes', 'ubigeos', 'channelsales'));
    }

    // public function edit()
    // {
    //     $this->resetValidation();
    //     $this->open = true;
    // }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->client->save();
            $this->client->direccion()->updateOrCreate([
                'id' => $this->direccion->id ?? null
            ], [
                'name' => $this->direccion->name,
                'ubigeo_id' => $this->direccion->ubigeo_id
            ]);

            DB::commit();
            // $this->open = false;
            $this->dispatchBrowserEvent('updated');
            $this->resetValidation();
            $this->client->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodalcontacto()
    {
        $this->reset(['contact', 'document2', 'name2', 'telefono2']);
        $this->resetValidation(['contact', 'document2', 'name2', 'telefono2']);
        $this->opencontacto = true;
    }

    public function editcontacto(Contact $contact)
    {
        $this->contact = $contact;
        $this->reset(['document2', 'name2', 'telefono2']);
        $this->resetValidation(['contact', 'document2', 'name2', 'telefono2']);
        $this->document2 = trim($contact->document);
        $this->name2 = trim($contact->name);
        if ($contact->telephone) {
            $this->telefono2 = trim($contact->telephone->phone);
        }
        $this->opencontacto = true;
    }

    public function savecontacto()
    {

        $this->document2 = trim($this->document2);
        $this->name2 = trim($this->name2);
        $this->validate([
            'document2' => ['required', 'numeric', 'digits:8'],
            'name2' => ['required', 'string', 'min:3'],
            'telefono2' => ['required', 'numeric'],
        ]);

        try {

            DB::beginTransaction();
            $contact = $this->client->contacts()->updateOrCreate([
                'id' => $this->contact->id ?? null
            ], [
                'document' => $this->document2,
                'name' => $this->name2
            ]);

            $contact->telephone()->updateOrCreate([
                'id' => $this->contact->telephone->id ?? null
            ], [
                'phone' => $this->telefono2
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->reset(['opencontacto', 'document2', 'name2', 'telefono2', 'contact']);
            $this->resetValidation(['contact', 'document2', 'name2', 'telefono2']);
            $this->client->refresh();
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
        $this->reset(['newtelefono']);
        $this->resetValidation(['telephone', 'newtelefono']);
        $this->telephone = $telephone;
        $this->newtelefono = trim($telephone->phone);
        $this->openphone = true;
    }

    public function openmodalphone()
    {
        $this->reset(['newtelefono', 'telephone']);
        $this->resetValidation(['telephone', 'newtelefono']);
        $this->openphone = true;
    }

    public function savephone()
    {
        $this->telefono2 = trim($this->telefono2);
        $this->validate([
            'newtelefono' => ['required', 'numeric', 'digits_between:7,9']
        ]);
        try {

            DB::beginTransaction();

            $this->client->telephones()->updateOrCreate([
                'id' => $this->telephone->id ?? null
            ], [
                'phone' => $this->newtelefono
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->reset(['openphone', 'newtelefono', 'telephone']);
            $this->resetValidation(['telephone', 'newtelefono']);
            $this->client->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function delete(Client $client)
    {
        DB::beginTransaction();
        try {
            $client->contacts()->delete();
            $client->telephones()->delete();
            $client->direccions()->delete();
            $client->deleteOrFail();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
            return redirect()->route('admin.clientes');
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
        $telephone->deleteOrFail();
        $this->client->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function deletecontacto(Contact $contact)
    {
        $contact->telephone->delete();
        $contact->deleteOrFail();
        $this->client->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function searchclient()
    {

        $this->client->document = trim($this->client->document);
        $this->validate([
            'client.document' => ['required', 'numeric', new ValidateDocument]
        ]);

        $this->client->name = null;
        // $this->client->pricetype_id = null;

        $this->resetValidation(['client.document', 'client.name', 'client.pricetype_id']);

        $http = new GetClient();
        $response = $http->getClient($this->client->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->client->name = $response->getData()->name;
                $this->client->pricetype_id = $response->getData()->pricetype_id;
            } else {
                $this->addError('client.document', $response->getData()->message);
            }
        } else {
            $this->addError('client.document', 'Error al buscar cliente.');
        }
    }

    public function searchcontacto()
    {

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
                $this->name2= $response->getData()->name;
                $this->telefono2 = $response->getData()->telefono;
            } else {
                $this->addError('document2', $response->getData()->message);
            }
        } else {
            $this->addError('document2', 'Error al buscar datos del contacto.');
        }
    }


    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-editclient-select2');
    }
}
