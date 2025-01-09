<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Models\Client;
use App\Models\Direccion;
use App\Models\Pricetype;
use App\Models\Ubigeo;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateContacto;
use App\Rules\ValidateDocument;
use App\Rules\ValidateNacimiento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
    public $direccions = [];

    protected function rules()
    {
        return [
            'document' => ['required', 'numeric', new ValidateDocument, 'regex:/^\d{8}(?:\d{3})?$/', new CampoUnique('clients', 'document', null, true)],
            'name' => ['required', 'string', 'min:8'],
            'email' => ['nullable', 'email'],
            'sexo' => ['required', 'string', 'min:1', 'max:1', Rule::in(['M', 'F', Client::EMPRESA])],
            'nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'pricetype_id' => ['nullable', Rule::requiredIf(view()->shared('empresa')->usarlista()), 'integer', 'min:1', 'exists:pricetypes,id'],
            'telefono' => ['nullable', 'numeric', 'digits:9', 'regex:/^\d{9}$/'],
            'documentContact' => ['nullable', Rule::requiredIf($this->addcontacto), new ValidateContacto($this->document)],
            'nameContact' => ['nullable', Rule::requiredIf($this->addcontacto), new ValidateContacto($this->document)],
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
        $this->resetExcept(['open', 'documentContact', 'nameContact', 'telefonoContact']);
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

        if (count($this->direccions) == 0) {
            $mensaje =  response()->json([
                'title' => "NO SE HAN AGREGADO DIRECCIONES DEL DOMICILIO",
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {

            $client = Client::onlyTrashed()->where('document', $this->document)->take(1)->first();

            if ($client) {
                $client->restore();
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

            foreach ($this->direccions as $item) {
                $ubigeo_id = $item['ubigeo_id'];
                if (empty($ubigeo_id)) {
                    $ubigeo_id = Ubigeo::query()->select('id', 'distrito', 'provincia')
                        ->whereRaw('LOWER(distrito) = ?', strtolower(trim($item['distrito'])))
                        ->whereRaw('LOWER(provincia) = ?', strtolower(trim($item['provincia'])))
                        ->take(1)->first()->id;
                }
                $client->direccions()->firstOrCreate([
                    'name' => $item['direccion'],
                    'ubigeo_id' => $ubigeo_id ?? null,
                    'default' => $item['principal'] ? Direccion::DEFAULT : 0,
                ]);
            }

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

    public function searchclient($property, $name)
    {
        $this->authorize('admin.clientes.create');
        $this->resetValidation();
        $this->$property = trim($this->$property);

        if ($property == 'document') {
            $rules = [
                'document' => ['required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/', new ValidateDocument, new CampoUnique('clients', 'document', null, true)]
            ];
        } else {
            $rules = [
                'documentContact' => ['required', 'numeric', 'digits:8', 'regex:/^\d{8}$/']
            ];
        }

        $this->validate($rules);
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->$property,
            'autosaved' =>  false,
            'savedireccions' => false,
            'searchbd' => $property == 'document' ? false : true,
            'obtenerlista' => $property == 'document' ? true : false,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());

            if (isset($cliente->success) && $cliente->success) {
                $this->$name = $cliente->name;
                if ($property == 'document') {
                    $direccions[] = [
                        'direccion' => $cliente->direccion,
                        'distrito' => $cliente->distrito,
                        'provincia' => $cliente->provincia,
                        'region' => $cliente->region,
                        'ubigeo_id' => $cliente->ubigeo_id,
                        'save' => true,
                        'principal' => true,
                    ];

                    if (array_key_exists('establecimientos', (array) $cliente) && count((array) $cliente->establecimientos) > 0) {
                        foreach ($cliente->establecimientos as $local) {
                            $ubigeolocal = null;
                            if (!empty($local->distrito)) {
                                $ubigeolocal = Ubigeo::query()->select('id', 'distrito', 'provincia', 'region')
                                    ->whereRaw('LOWER(distrito) = ?', [strtolower(trim($local->distrito))])
                                    ->whereRaw('LOWER(provincia) = ?', [strtolower(trim($local->provincia))])
                                    ->take(1)->first();
                            }

                            $direccions[] = [
                                'direccion' => $local->direccion,
                                'distrito' => $local->distrito,
                                'provincia' => $local->provincia,
                                'region' => $local->departamento,
                                'ubigeo_id' => $ubigeolocal->ubigeo_id ?? null,
                                'save' => true,
                                'principal' => false,
                            ];
                            $this->direccions = $direccions;
                        }
                    }

                    $this->direccions = $direccions;
                    $this->exists = true;
                    if (view()->shared('empresa')->usarLista() && !empty($cliente->pricetype)) {
                        $this->pricetype_id = $cliente->pricetype->id;
                    }

                    if (Module::isEnabled('Marketplace')) {
                        $user = User::where('document', $this->document)->first();
                        if ($user) {
                            $this->user = $user;
                        }
                    }
                } else {
                    $this->telefonoContact = $cliente->telefono;
                }
            } else {
                $this->$name = '';
                if ($property == 'document') {
                    $this->direccion = '';
                    $this->ubigeo_id = '';
                    $this->pricetype_id = null;
                    $this->user = null;
                } else {
                    $this->telefonoContact = '';
                }
                $this->addError($property, $cliente->error);
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

    public function adddireccion()
    {
        $this->direccion = trim(mb_strtoupper($this->direccion, "UTF-8"));
        $this->validate([
            'ubigeo_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccion' => ['required', 'string', 'min:3'],
        ]);

        if (count($this->direccions) > 0) {
            $direcciones = array_column($this->direccions, 'direccion');
            if (in_array($this->direccion, $direcciones)) {
                $this->addError('direccion', "La dirección ya existe.");
                return false;
            }
        }

        $ubigeo = Ubigeo::find($this->ubigeo_id);
        $principal = count($this->direccions) > 0 ? true : false;

        $direccions =  $this->direccions;
        $direccions[] = [
            'direccion' => $this->direccion,
            'distrito' => $ubigeo->distrito,
            'provincia' => $ubigeo->provincia,
            'region' => $ubigeo->region,
            'ubigeo_id' => $this->ubigeo_id,
            'save' => true,
            'principal' => $principal,
        ];
        $this->direccions = array_values($direccions);
        $this->reset(['direccion', 'ubigeo_id']);
        $this->resetValidation();
    }

    public function deletedireccion($index)
    {
        unset($this->direccions[$index]);
        $this->direccions = array_values($this->direccions);
        $this->resetValidation();
    }
}
