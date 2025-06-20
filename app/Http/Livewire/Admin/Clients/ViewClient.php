<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Direccion;
use App\Models\Pricetype;
use App\Models\Telephone;
use App\Models\Ubigeo;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateDireccion;
use App\Rules\ValidateDocument;
use App\Rules\ValidatePhoneClient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;

class ViewClient extends Component
{

    use AuthorizesRequests;

    public $opencontacto = false;
    public $openphone = false;
    public $opendireccion = false;
    public $client, $contact, $telephone;

    public $document2, $name2, $telefono2, $newtelefono;
    public $direccion, $namedireccion, $ubigeo_id;
    public $user;

    protected function rules()
    {
        return [
            'client.document' => [
                'required',
                'integer',
                'numeric',
                'digits_between:8,11',
                new ValidateDocument,
                new CampoUnique('clients', 'document', $this->client->id, true)
            ],
            'client.name' => ['required', 'min:3'],
            'client.email' => ['nullable', 'min:6', 'email'],
            'client.sexo' => ['nullable', 'string', 'min:1', Rule::in(['M', 'F', 'E'])],
            'client.nacimiento' => ['nullable', 'date'],
            'client.pricetype_id' => ['nullable', Rule::requiredIf(mi_empresa()->usarlista()), 'integer', 'min:1', 'exists:pricetypes,id'],
        ];
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->contact = new Contact();
        $this->direccion = new Direccion();

        if (!$client->user) {
            if (Module::isEnabled('Marketplace')) {
                $user = User::where('document', $client->document)->first();
                if ($user) {
                    $this->user = $user;
                }
            }
        }
    }

    public function render()
    {
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->get();
        $ubigeos = Ubigeo::query()->select('id', 'region', 'provincia', 'distrito', 'ubigeo_reniec')
            ->orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        return view('livewire.admin.clients.view-client', compact('pricetypes', 'ubigeos'));
    }

    public function update()
    {

        $this->authorize('admin.clientes.edit');
        $this->validate();
        DB::beginTransaction();
        try {
            $this->client->save();
            DB::commit();
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
        $this->authorize('admin.clientes.contacts.edit');
        $this->reset(['contact', 'document2', 'name2', 'telefono2']);
        $this->resetValidation();
        $this->opencontacto = true;
    }

    public function editcontacto(Contact $contact)
    {
        $this->authorize('admin.clientes.contacts.edit');
        $this->contact = $contact;
        $this->reset(['document2', 'name2', 'telefono2']);
        $this->resetValidation();
        $this->document2 = trim($contact->document);
        $this->name2 = trim($contact->name);
        if ($contact->telephone) {
            $this->telefono2 = trim($contact->telephone->phone);
        }
        $this->opencontacto = true;
    }

    public function savecontacto()
    {

        $this->authorize('admin.clientes.contacts.edit');
        $this->document2 = trim($this->document2);
        $this->name2 = trim($this->name2);
        $this->validate([
            'document2' => ['required', 'numeric', 'digits:8'],
            'name2' => ['required', 'string', 'min:3'],
            'telefono2' => ['nullable', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
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

    public function editphone(Telephone $telephone)
    {
        $this->authorize('admin.clientes.phones.edit');
        $this->reset(['newtelefono']);
        $this->resetValidation(['telephone', 'newtelefono']);
        $this->telephone = $telephone;
        $this->newtelefono = trim($telephone->phone);
        $this->openphone = true;
    }

    public function openmodalphone()
    {
        $this->authorize('admin.clientes.phones.edit');
        $this->reset(['newtelefono', 'telephone']);
        $this->resetValidation(['telephone', 'newtelefono']);
        $this->openphone = true;
    }

    public function savephone()
    {
        $this->authorize('admin.clientes.phones.edit');
        $this->telefono2 = trim($this->telefono2);
        $this->validate([
            'newtelefono' => [
                'required',
                'numeric',
                'digits:9',
                'regex:/^\d{9}$/',
                new ValidatePhoneClient('clients', $this->client->id, $this->telephone->id ?? null)
            ]
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

    public function editdireccion(Direccion $direccion)
    {
        // $this->authorize('admin.clientes.contacts.edit');
        $this->direccion = $direccion;
        $this->namedireccion = $direccion->name;
        $this->ubigeo_id = $direccion->ubigeo_id;
        $this->opendireccion = true;
    }

    public function updatingOpendireccion()
    {
        if ($this->opendireccion == false) {
            $this->reset(['namedireccion', 'direccion', 'ubigeo_id']);
            $this->resetValidation();
        }
    }

    public function savedireccion($closemodal = false)
    {
        $this->validate([
            'namedireccion' => [
                'required',
                'string',
                'min:3',
                new ValidateDireccion($this->client->id, $this->direccion->id ?? null),
            ],
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
        ]);

        // $this->authorize('admin.clientes.edit');
        DB::beginTransaction();
        try {
            $this->client->direccion()->updateOrCreate([
                'id' => $this->direccion->id ?? null
            ], [
                'name' => $this->namedireccion,
                'ubigeo_id' => $this->ubigeo_id
            ]);

            DB::commit();
            if ($this->direccion) {
                $this->dispatchBrowserEvent('updated');
            } else {
                $this->dispatchBrowserEvent('created');
            }
            $this->reset(['direccion', 'namedireccion', 'ubigeo_id']);
            if ($closemodal) {
                $this->opendireccion = false;
            }
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

    public function deletedireccion(Direccion $direccion)
    {
        $direccion->forceDelete();
        $this->dispatchBrowserEvent('deleted');
        $this->client->refresh();
    }

    public function delete(Client $client)
    {
        $this->authorize('admin.clientes.delete');
        DB::beginTransaction();
        try {
            $client->contacts()->forceDelete();
            $client->telephones()->delete();
            $client->direccions()->forceDelete();
            $client->delete();
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

    public function usedefault(Direccion $direccion)
    {
        DB::beginTransaction();
        try {
            $this->client->direccions()->update(['default' => '0']);
            $direccion->default = 1;
            $direccion->save();
            DB::commit();
            $this->client->refresh();
            $this->dispatchBrowserEvent('toast', toastJSON('Dirección por defecto actualizado correctamente'));
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
        $this->authorize('admin.clientes.phones.edit');
        $telephone->delete();
        $this->client->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function deletecontacto(Contact $contact)
    {
        $this->authorize('admin.clientes.contacts.edit');
        $contact->telephone->delete();
        $contact->delete();
        $this->client->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function searchclient()
    {
        $this->authorize('admin.clientes.edit');
        $this->resetValidation();
        $this->client->document = trim($this->client->document);
        $this->validate([
            'client.document' => ['required', 'numeric', new ValidateDocument]
        ]);

        // $this->client->name = null;
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->client->document,
            'obtenerlista' => view()->shared('empresa')->usarLista() ? true : false,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                if (!empty($cliente->direccion)) {
                    $others = [
                        'ubigeo_id' => !empty($cliente->ubigeo_id) ? $cliente->ubigeo_id : null,
                    ];
                    if ($this->client->direccions()->default()->count() == 0) {
                        $others['default'] =  Direccion::DEFAULT;
                    }
                    $this->client->direccions()->updateOrCreate([
                        'name' => trim($cliente->direccion)
                    ], $others);
                    $this->client->refresh();
                }

                $this->client->name = $cliente->name;
                if (view()->shared('empresa')->usarLista() && !empty($cliente->pricetype)) {
                    $this->client->pricetype_id = $cliente->pricetype->id;
                }
            } else {
                $this->client->refresh();
                $this->addError('client.document', $cliente->error);
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function searchcontacto()
    {
        $this->authorize('admin.clientes.contacts.edit');
        $this->resetValidation();
        $this->document2 = trim($this->document2);
        $this->validate([
            'document2' => ['required', 'numeric', 'digits:8']
        ]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document2,
            'searchbd' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->name2 = $cliente->name;
                $this->telefono2 = $cliente->telefono;
            } else {
                $this->name2 = '';
                $this->telefono2 = '';
                $this->addError('document2', $cliente->error);
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }

    public function sincronizeuser()
    {
        if (Module::isEnabled('Marketplace') && empty($this->client->user)) {
            $user = User::doesntHave('client')->where('document', $this->client->document)->first();
            if ($user) {
                $this->client->user_id = $user->id;
                $this->client->save();
                $this->client->refresh();
                $this->resetValidation();
                $this->reset(['user']);
                $this->dispatchBrowserEvent('updated');
            }
        }
    }
}
