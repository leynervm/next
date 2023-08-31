<?php

namespace App\Http\Livewire\Admin\Clients;

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
use App\Rules\ValidateNacimiento;
use App\Rules\ValidatePhoneClient;
use App\Rules\ValidatePhoneContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewClient extends Component
{

    public $client;
    public $contact;
    public $open = false;
    public $opencontact = false;
    public $opendireccion = false;
    public $openphone = false;
    public $openphonecontacto = false;

    public $telephone, $direccion;

    public $newtelefono, $newdireccion, $ubigeo_id,
        $newtelefonocontacto, $documentcontacto, $namecontacto, $telefonocontacto;

    protected $listeners = ['delete', 'deletecontacto', 'deletephone', 'deletedireccion'];


    protected function rules()
    {
        return [
            'client.document' => [
                'required', 'integer', 'numeric', 'digits_between:8,11',
                new CampoUnique('clients', 'document', $this->client->id, true)
            ],
            'client.name' => ['required', 'min:3'],
            'client.email' => ['nullable', 'email'],
            'client.sexo' => ['nullable'],
            'client.nacimiento' => ['nullable', new ValidateNacimiento()],
            'client.pricetype_id' => ['required', 'integer', 'exists:pricetypes,id'],
            'client.channelsale_id' => ['required', 'integer', 'exists:channelsales,id'],
        ];
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->contact = new Contact();
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $ubigeos = Ubigeo::all();
        $channelsales = Channelsale::orderBy('name', 'asc')->get();
        return view('livewire.admin.clients.view-client', compact('pricetypes', 'ubigeos', 'channelsales'));
    }

    public function edit()
    {
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->client->save();
        $this->open = false;
        $this->dispatchBrowserEvent('updated');
        $this->resetValidation();
        $this->client->refresh();
    }

    public function openmodalphone()
    {
        $this->reset(['newtelefono', 'telephone']);
        $this->openphone = true;
    }

    public function savetelefono()
    {

        $this->newtelefono = trim($this->newtelefono);
        $validateData = $this->validate([
            'newtelefono' => [
                'required', 'numeric', 'digits_between:6,9',
                new ValidatePhoneClient($this->client->id, $this->newtelefono, $this->telephone->id ?? null)
            ],
            'client.id' => 'required|exists:clients,id'
        ]);

        $event = 'created';

        DB::beginTransaction();
        try {
            if ($this->client) {
                if ($this->telephone) {
                    $event = 'updated';
                    $this->telephone->phone = $this->newtelefono;
                    $this->telephone->user_id = Auth::user()->id;
                    $this->telephone->save();
                } else {
                    $this->client->telephones()->create([
                        'phone' => $this->newtelefono,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent($event);
            $this->client->refresh();
            $this->reset(['telephone', 'newtelefono', 'openphone']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function editTelefono(Telephone $telephone)
    {
        $this->telephone = $telephone;
        $this->newtelefono = $telephone->phone;
        $this->openphone = true;
    }

    public function deleteTelefono(Telephone $telephone)
    {
        $telephone->delete();
        $this->dispatchBrowserEvent('deleted');
        $this->client->refresh();
    }


    public function openmodaldireccion()
    {
        $this->reset(['direccion', 'ubigeo_id', 'newdireccion']);
        $this->opendireccion = true;
    }

    public function editDireccion(Direccion $direccion)
    {
        $this->direccion = $direccion;
        $this->newdireccion = $direccion->name;
        // $this->ubigeo_id = $direccion->ubigeo_id;
        $this->opendireccion = true;
    }

    public function saveDireccion()
    {
        $this->newdireccion = trim($this->newdireccion);
        $validateData = $this->validate([
            'newdireccion' => [
                'required', 'min:3', new ValidateDireccion($this->client->id, $this->newdireccion, $this->direccion->id ?? null)
            ],
            'ubigeo_id' => ['nullable'],
            'client.id' => 'required|exists:clients,id'
        ]);
        $event = 'created';

        DB::beginTransaction();
        try {
            if ($this->client) {
                if ($this->direccion) {
                    $event = 'updated';
                    $this->direccion->name = $this->newdireccion;
                    // $this->ubigeo_id->ubigeo_id = $this->ubigeo_id;
                    $this->direccion->user_id = Auth::user()->id;
                    $this->direccion->save();
                } else {
                    $this->client->direccions()->create([
                        'name' => $this->newdireccion,
                        'ubigeo_id' => $this->ubigeo_id,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();
            $this->reset(['direccion', 'ubigeo_id', 'newdireccion', 'opendireccion']);
            $this->dispatchBrowserEvent($event);
            $this->client->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodalphonecontacto(Contact $contact)
    {
        $this->resetValidation();
        $this->reset(['contact', 'newtelefono', 'newtelefonocontacto', 'telephone']);
        $this->contact = $contact;
        $this->openphonecontacto = true;
    }

    public function editphonecontacto(Contact $contact, Telephone $telephone)
    {
        $this->telephone = $telephone;
        $this->contact = $contact;
        $this->newtelefonocontacto = $telephone->phone;
        $this->resetValidation();
        $this->openphonecontacto = true;
    }

    public function savetelefonocontacto()
    {

        $this->newtelefonocontacto = trim($this->newtelefonocontacto);
        $validateData = $this->validate([
            'newtelefonocontacto' => [
                'required', 'numeric', 'digits_between:6,9',
                new ValidatePhoneContact($this->contact->id, $this->newtelefonocontacto, $this->telephone->id ?? null)
            ],
            'contact.id' => 'required|exists:contacts,id'
        ]);

        $event = 'created';

        DB::beginTransaction();
        try {
            if ($this->telephone) {
                $event = 'updated';
                $this->telephone->phone = $this->newtelefonocontacto;
                $this->telephone->user_id = Auth::user()->id;
                $this->telephone->save();
            } else {
                if ($this->contact) {
                    $this->contact->telephones()->create([
                        'phone' => $this->newtelefonocontacto,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }

            DB::commit();
            $this->resetValidation();
            $this->reset(['contact', 'telephone', 'newtelefonocontacto', 'openphonecontacto']);
            $this->dispatchBrowserEvent($event);
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
        $this->reset(['contact', 'documentcontacto', 'namecontacto', 'telefonocontacto']);
        $this->resetValidation();
        $this->opencontact = true;
    }

    public function editcontacto(Contact $contact)
    {
        $this->resetValidation();
        $this->contact = $contact;
        $this->documentcontacto = trim($this->contact->document);
        $this->namecontacto = trim($this->contact->name);
        $this->opencontact = true;
    }

    public function savecontacto()
    {

        $this->documentcontacto = trim($this->documentcontacto);
        $this->namecontacto = trim($this->namecontacto);
        $this->telefonocontacto = trim($this->telefonocontacto);
        $validateData = $this->validate([
            'documentcontacto' => [
                'required', 'numeric', 'digits:8',
                new ValidateContactClient($this->client->id, $this->documentcontacto, $this->contact->id ?? null)
            ],
            'namecontacto' => ['required', 'string'],
            'telefonocontacto' => [$this->contact ? 'nullable' : 'required', 'numeric', 'digits_between:6,9'],
            'client.id' => ['required', 'exists:clients,id'],
        ]);

        try {

            $event = 'created';
            DB::beginTransaction();

            if ($this->client) {
                if ($this->contact) {
                    $event = 'updated';
                    $this->contact->document = trim($this->documentcontacto);
                    $this->contact->name = trim($this->namecontacto);
                    $this->contact->user_id = Auth::user()->id;
                    $this->contact->save();
                } else {
                    $contact = $this->client->contacts()->create([
                        'document' => trim($this->documentcontacto),
                        'name' => trim($this->namecontacto),
                        'user_id' => Auth::user()->id,
                    ]);

                    $contact->telephones()->create([
                        'phone' => $this->telefonocontacto,
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }
            DB::commit();
            $this->client->refresh();
            $this->dispatchBrowserEvent($event);
            $this->reset(['opencontact', 'contact', 'documentcontacto', 'namecontacto', 'telefonocontacto']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmdelete(Client $client)
    {
        $this->dispatchBrowserEvent('client.confirmDelete', $client);
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

    public function confirmdeletephone(Telephone $telephone)
    {
        $this->dispatchBrowserEvent('client.confirmDeletephone', $telephone);
    }

    public function deletephone(Telephone $telephone)
    {
        $telephone->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
        $this->client->refresh();
    }

    public function confirmdeletecontacto(Contact $contact)
    {
        $this->dispatchBrowserEvent('client.confirmDeletecontacto', $contact);
    }

    public function deletecontacto(Contact $contact)
    {
        $contact->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
        $this->client->refresh();
    }

    public function confirmdeletedireccion(Direccion $direccion)
    {
        $this->dispatchBrowserEvent('client.confirmDeletedireccion', $direccion);
    }

    public function deletedireccion(Direccion $direccion)
    {
        $direccion->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
        $this->client->refresh();
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-editclient-select2');
    }
}
