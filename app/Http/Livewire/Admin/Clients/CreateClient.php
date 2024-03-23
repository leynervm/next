<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Helpers\GetClient;
use App\Models\Client;
use App\Models\Pricetype;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateContacto;
use App\Rules\ValidateDocument;
use App\Rules\ValidateNacimiento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateClient extends Component
{

    use AuthorizesRequests;

    public $open = false;

    public $document, $name, $ubigeo_id, $direccion, $email,
        $sexo, $nacimiento, $pricetype_id, $telefono;
    public $documentContact, $nameContact, $telefonoContact;
    public $exists = false;

    protected function rules()
    {
        return [
            'document' => [
                'required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('clients', 'document', null, true)
            ],
            'name' => ['required', 'string', 'min:8'],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccion' => ['required', 'string', 'min:3'],
            'email' => ['nullable', 'email'],
            'sexo' => [
                'required', 'string', 'min:1', 'max:1',
                Rule::in(['M', 'F', 'E'])
            ],
            'nacimiento' => [
                'nullable', 'date',
                // new ValidateNacimiento(10)
            ],
            'pricetype_id' => [
                'nullable',
                Rule::requiredIf(mi_empresa()->usarlista()),
                'integer', 'min:1', 'exists:pricetypes,id'
            ],
            'telefono' => ['required', 'numeric', 'digits_between:7,9'],
            'documentContact' => [
                new ValidateContacto($this->document)
            ],
            'nameContact' => [
                new ValidateContacto($this->document)
            ],
            'telefonoContact' => [
                new ValidateContacto($this->document)
            ],
        ];
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.clients.create-client', compact('pricetypes', 'ubigeos'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.clientes.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function limpiarcliente()
    {
        $this->reset(['document', 'name', 'direccion', 'ubigeo_id', 'exists']);
    }

    public function save()
    {

        $this->authorize('admin.clientes.create');
        if (strlen(trim($this->document)) == 11) {
            $this->sexo = 'E';
        }

        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->email = trim($this->email);
        $this->validate();

        DB::beginTransaction();
        try {

            $client = Client::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($client) {
                $client->document = $this->document;
                $client->name = $this->name;
                $client->email = $this->email;
                $client->nacimiento = $this->nacimiento;
                $client->sexo = $this->sexo;
                $client->pricetype_id = $this->pricetype_id;
                $client->restore();
            } else {
                $client = Client::create([
                    'date' => now('America/Lima'),
                    'document' => $this->document,
                    'name' => $this->name,
                    'email' => $this->email,
                    'nacimiento' => $this->nacimiento,
                    'sexo' => $this->sexo,
                    'pricetype_id' => $this->pricetype_id,
                ]);
            }

            $default = $client->direccions()->exists() ? 0 : 1;
            $client->direccions()->create([
                'name' => $this->direccion,
                'ubigeo_id' => $this->ubigeo_id,
                'default' => $default,
            ]);

            $client->telephones()->create([
                'phone' => $this->telefono
            ]);

            if (strlen(trim($this->document)) == 11) {
                $contact = $client->contacts()->create([
                    'document' => $this->documentContact,
                    'name' => $this->nameContact
                ]);

                $contact->telephone()->create([
                    'phone' => $this->telefonoContact
                ]);
            }

            DB::commit();
            $this->emitTo('admin.clients.show-clients', 'render');
            $this->reset();
            $this->dispatchBrowserEvent('created');
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

        $this->authorize('admin.clientes.create');
        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/',
                new ValidateDocument
            ]
        ]);

        $this->resetValidation();
        $this->reset(['name', 'direccion', 'ubigeo_id']);
        $http = new GetClient();
        $response = $http->getSunat($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->exists = true;
                $this->name = $response->getData()->name;
                $this->direccion = $response->getData()->direccion;
                $this->ubigeo_id = $response->getData()->ubigeo_id;
            } else {
                $this->addError('document', $response->getData()->message);
            }
        } else {
            $this->addError('document', 'Error al buscar cliente.');
        }
    }

    public function searchcontacto()
    {

        $this->authorize('admin.clientes.create');
        $this->documentContact = trim($this->documentContact);
        $this->validate([
            'documentContact' => ['required', 'numeric', 'digits:8', 'regex:/^\d{8}$/']
        ]);

        $this->nameContact = null;
        $this->telefonoContact = null;
        $this->resetValidation(['documentContact', 'nameContact', 'telefonoContact']);

        $http = new GetClient();
        $response = $http->getClient($this->documentContact, false);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->nameContact = $response->getData()->name;
                $this->telefonoContact = $response->getData()->telefono;
            } else {
                $this->addError('nameContact', $response->getData()->message);
            }
        } else {
            $this->addError('nameContact', 'Error al buscar datos del contacto.');
        }
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-client-select2');
    }
}
