<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Models\Channelsale;
use App\Models\Client;
use App\Models\Pricetype;
use App\Models\Telephone;
use App\Models\Ubigeo;
use App\Rules\CampoUnique;
use App\Rules\ValidateContacto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateClient extends Component
{

    public $open = false;
    public $mostrarcontacto = false;

    public $document, $name, $ubigeo_id, $direccion, $email,
        $sexo, $nacimiento, $pricetype_id, $channelsale_id, $telefono;

    public $documentContact, $nameContact, $telefonoContact;

    protected function rules()
    {
        return [
            'document' => [
                'required', 'integer', 'numeric', 'digits_between:8,11', 'unique:clients,document',
                new CampoUnique('clients', 'document', null, true)
            ],
            'name' => ['required', 'min:3'],
            'ubigeo_id' => ['nullable'],
            'direccion' => ['required', 'min:3'],
            'email' => ['nullable', 'email'],
            'sexo' => ['nullable'],
            'nacimiento' => ['nullable'],
            'pricetype_id' => ['required', 'integer', 'exists:pricetypes,id'],
            'channelsale_id' => ['required', 'integer', 'exists:channelsales,id'],
            'telefono' => ['required'],
            'documentContact' => [new ValidateContacto($this->document)],
            'nameContact' => [new ValidateContacto($this->document)],
            'telefonoContact' => [new ValidateContacto($this->document)],
        ];
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $ubigeos = Ubigeo::all();
        $channelsales = Channelsale::orderBy('name', 'asc')->get();
        return view('livewire.admin.clients.create-client', compact('pricetypes', 'ubigeos', 'channelsales'));
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
        $this->mostrarcontacto = strlen(trim($this->document)) == 11 ? true : false;
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
                $client->direccion = $this->direccion;
                $client->email = $this->email;
                $client->nacimiento = $this->nacimiento;
                $client->sexo = $this->sexo;
                $client->pricetype_id = $this->pricetype_id;
                $client->channelsale_id = $this->channelsale_id;
                // $client->user_id = Auth::user()->id;
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
                    'channelsale_id' => $this->channelsale_id,
                    // 'user_id' => Auth::user()->id,
                ]);
            }

            $client->direccions()->create([
                'name' => $this->direccion,
                'ubigeo_id' => $this->ubigeo_id,
                'user_id' => Auth::user()->id,
            ]);

            $client->telephones()->create([
                'phone' => $this->telefono,
                'user_id' => Auth::user()->id,
            ]);

            if (strlen(trim($this->document)) == 11) {
                $contact = $client->contacts()->create([
                    'document' => $this->documentContact,
                    'name' => $this->nameContact,
                    'user_id' => Auth::user()->id,
                ]);

                $contact->telephones()->create([
                    'phone' => $this->telefonoContact,
                    'user_id' => Auth::user()->id,
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

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-client-select2');
    }
}
