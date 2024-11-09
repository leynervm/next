<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Helpers\GetClient;
use App\Models\Client;
use App\Models\Pricetype;
use App\Models\Ubigeo;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateContacto;
use App\Rules\ValidateDocument;
use App\Rules\ValidateNacimiento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class CreateClient extends Component
{

    use AuthorizesRequests;

    public $open = false;

    public $document, $name, $ubigeo_id, $direccion, $email,
        $sexo, $nacimiento, $pricetype_id, $telefono;
    public $documentContact, $nameContact, $telefonoContact;
    public $user;
    public $exists = false;
    public $addcontacto = false;

    protected function rules()
    {
        return [
            'document' => [
                'required',
                'numeric',
                'digits_between:8,11',
                'regex:/^\d{8}(?:\d{3})?$/',
                new CampoUnique('clients', 'document', null, true)
            ],
            'name' => ['required', 'string', 'min:8'],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccion' => ['required', 'string', 'min:3'],
            'email' => ['nullable', 'email'],
            'sexo' => ['required', 'string', 'min:1', 'max:1', Rule::in(['M', 'F', 'E'])],
            'nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'pricetype_id' => [
                'nullable',
                Rule::requiredIf(mi_empresa()->usarlista()),
                'integer',
                'min:1',
                'exists:pricetypes,id'
            ],
            'telefono' => ['nullable', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'documentContact' => [
                'nullable',
                Rule::requiredIf($this->addcontacto),
                new ValidateContacto($this->document)
            ],
            'nameContact' => [
                'nullable',
                Rule::requiredIf($this->addcontacto),
                new ValidateContacto($this->document)
            ],
            'telefonoContact' => ['nullable', 'numeric', 'digits:9', 'regex:/^\d{9}$/', new ValidateContacto($this->document)],
        ];
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('id', 'asc')->get();
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
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
        $this->reset(['document', 'name', 'direccion', 'ubigeo_id', 'user', 'exists', 'addcontacto']);
    }


    public function save($closemodal = false)
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

            $client = Client::onlyTrashed()->where('document', $this->document)->first();

            if ($client) {
                $client->restore();
                $client->document = $this->document;
                $client->name = $this->name;
                $client->email = $this->email;
                $client->nacimiento = $this->nacimiento;
                $client->sexo = $this->sexo;
                $client->pricetype_id = $this->pricetype_id;
                $client->save();
            } else {
                $client = Client::create([
                    'date' => now('America/Lima'),
                    'document' => $this->document,
                    'name' => $this->name,
                    'email' => $this->email,
                    'nacimiento' => $this->nacimiento,
                    'sexo' => $this->sexo,
                    'pricetype_id' => $this->pricetype_id,
                    'user_id' =>  Module::isEnabled('Marketplace') ? $this->user->id ?? null : null,
                ]);
            }

            $default = $client->direccions()->exists() ? 0 : 1;
            $client->direccions()->create([
                'name' => $this->direccion,
                'ubigeo_id' => $this->ubigeo_id,
                'default' => $default,
            ]);

            if (trim($this->telefono) !== '') {
                $client->telephones()->create([
                    'phone' => $this->telefono
                ]);
            }

            if ($this->addcontacto && strlen(trim($this->document)) == 11) {
                $contact = $client->contacts()->create([
                    'document' => $this->documentContact,
                    'name' => $this->nameContact
                ]);

                if (trim($this->telefonoContact) !== '') {
                    $contact->telephone()->create([
                        'phone' => $this->telefonoContact
                    ]);
                }
            }

            DB::commit();
            $this->emitTo('admin.clients.show-clients', 'render');
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
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
            'document' => ['required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/', new ValidateDocument]
        ]);

        $this->resetValidation();
        $this->reset(['name', 'direccion', 'ubigeo_id', 'user']);
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

        if (Module::isEnabled('Marketplace')) {
            $user = User::where('document', $this->document)->first();
            if ($user) {
                $this->user = $user;
            }
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
}
