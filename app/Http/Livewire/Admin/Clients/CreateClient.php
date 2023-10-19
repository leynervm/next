<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Helpers\GetClient;
use App\Models\Client;
use App\Models\Pricetype;
use App\Models\Telephone;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateContacto;
use App\Rules\ValidateDocument;
use App\Rules\ValidateNacimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateClient extends Component
{

    public $open = false;
    public $mostrarcontacto = false;
    public $searchingclient = false;

    public $document, $name, $ubigeo_id, $direccion, $email,
        $sexo, $nacimiento, $pricetype_id, $telefono;

    public $documentContact, $nameContact, $telefonoContact;

    protected function rules()
    {
        return [
            'document' => [
                'required', 'integer', 'numeric', 'digits_between:8,11',
                new CampoUnique('clients', 'document', null, true)
            ],
            'name' => ['required', 'string', 'min:8'],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccion' => ['required', 'string', 'min:3'],
            'email' => ['nullable', 'min:6', 'email'],
            'sexo' => ['required'],
            'nacimiento' => ['nullable', 'date', new ValidateNacimiento(10)],
            'pricetype_id' => ['required', 'integer', 'min:1', 'exists:pricetypes,id'],
            'telefono' => ['required', 'numeric', 'digits_between:7,9'],
            'documentContact' => [new ValidateContacto($this->document)],
            'nameContact' => [new ValidateContacto($this->document)],
            'telefonoContact' => [new ValidateContacto($this->document)],
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
            $this->resetValidation();
            $this->reset();
        }
    }

    public function updatedDocument($value)
    {

        if (strlen(trim($this->document)) == 11) {
            $this->mostrarcontacto = true;
            $this->sexo = 'E';
        } else {
            $this->mostrarcontacto = false;
            $this->sexo = null;
        }
        // $this->mostrarcontacto = strlen(trim($this->document)) == 11 ? true : false;
    }

    public function save()
    {
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->email = $this->email;
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

            $client->direccion()->create([
                'name' => $this->direccion,
                'ubigeo_id' => $this->ubigeo_id
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

        $this->document = trim($this->document);
        $this->validate([
            'document' => ['required', 'numeric', new ValidateDocument]
        ]);

        $this->name = null;
        $this->direccion = null;
        $this->telefono = null;
        $this->pricetype_id = null;
        $this->ubigeo_id = null;

        $this->resetValidation(['document', 'name', 'direccion', 'telefono', 'pricetype_id', 'ubigeo_id']);

        $http = new GetClient();
        $response = $http->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->name = $response->getData()->name;
                $this->direccion = $response->getData()->direccion;
                $this->pricetype_id = $response->getData()->pricetype_id;
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

        $this->documentContact = trim($this->documentContact);
        $this->validate([
            'documentContact' => ['required', 'numeric', 'digits:8']
        ]);

        $this->nameContact = null;
        $this->telefonoContact = null;
        $this->resetValidation(['documentContact', 'nameContact', 'telefonoContact']);

        $http = new GetClient();
        $response = $http->getClient($this->documentContact);

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
