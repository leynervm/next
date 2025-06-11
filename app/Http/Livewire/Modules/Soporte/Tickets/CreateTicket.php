<?php

namespace App\Http\Livewire\Modules\Soporte\Tickets;

use App\Enums\EstadoEquipoEnum;
use App\Models\Areawork;
use App\Models\Caracteristica;
use App\Models\Client;
use App\Models\Marca;
use App\Models\Ubigeo;
use App\Rules\ValidateDocument;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Condition;
use Modules\Soporte\Entities\Priority;
use Modules\Soporte\Entities\Typeequipo;
use App\Traits\ManageArrayTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Modules\Soporte\Entities\Entorno;
use Modules\Soporte\Entities\Ticket;

class CreateTicket extends Component
{

    use ManageArrayTrait;

    public $areawork;
    public $priority_id, $areawork_id, $entorno_id, $atencion_id, $condition_id, $email;
    public $addequipo = false, $addadress = false;
    public $client_id, $document, $name, $direccion;
    public $documentcontact, $namecontact;
    public $entornos = [], $telefonos = [], $contacts = [], $selectedphones = [], $ubigeos = [], $equiposticket = [];
    public $typeequipo_id, $marca_id, $modelo, $serie, $stateinicial, $descripcion, $servicio;
    public $ubigeo_id, $direccionasistencia, $referencia, $dateasistencia;
    public $contact = [
        'document' => '',
        'name' => ''
    ];

    public $openphone = false;
    public $phonenumber;

    protected function rules()
    {
        return [
            'document' => ['required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/', new ValidateDocument],
            'name' => ['required', 'string', 'min:6'],
            'email' => ['nullable', 'email'],
            'areawork.id' => ['required', 'exists:areaworks,id'],
            'selectedphones' => ['required', 'array',  'min:1'],
            'contact' =>  ['nullable', 'array'],
            'priority_id' => [
                count($this->equiposticket) > 0 ? 'nullable' : 'required',
                'integer',
                'min:1',
                'exists:priorities,id'
            ],
            'atencion_id' => [
                count($this->equiposticket) > 0 ? 'nullable' : 'required',
                'integer',
                'min:1',
                'exists:atencions,id'
            ],
            'entorno_id' => [
                count($this->equiposticket) > 0 ? 'nullable' : 'required',
                'integer',
                'min:1',
                'exists:entornos,id'
            ],
            'condition_id' => [
                count($this->equiposticket) > 0 ? 'nullable' : 'required',
                'integer',
                'min:1',
                'exists:conditions,id'
            ],
            'typeequipo_id' => [
                count($this->equiposticket) > 0 || !$this->addequipo ? 'nullable' : 'required',
                'integer',
                'min:1',
                'exists:typeequipos,id'
            ],
            'marca_id' => [
                count($this->equiposticket) > 0 || !$this->addequipo ? 'nullable' : 'required',
                'integer',
                'min:1',
                'exists:marcas,id'
            ],
            'servicio' => [
                count($this->equiposticket) > 0 ? 'nullable' : 'required',
                'string',
                'min:6'
            ],
            'descripcion' => [
                count($this->equiposticket) > 0 || !$this->addequipo ? 'nullable' : 'required',
                'string',
                'min:3'
            ],
            'modelo' => [
                count($this->equiposticket) > 0 || !$this->addequipo ? 'nullable' : 'required',
                'string',
                'min:3'
            ],
            'serie' => ['nullable', 'string', 'min:3'],
            'stateinicial' => [
                count($this->equiposticket) > 0 || !$this->addequipo ? 'nullable' : 'required',
                'integer',
                new Enum(EstadoEquipoEnum::class)
            ],

            'ubigeo_id' => [$this->addadress ? 'required' : 'nullable', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccionasistencia' => [$this->addadress ? 'required' : 'nullable', 'string', 'min:6'],
            'referencia' => ['nullable', 'string', 'min:3'],
            'dateasistencia' => [$this->addadress ? 'required' : 'nullable', 'date', 'date_format:Y-m-d\TH:i', 'after_or_equal:today'],
            'equiposticket' => [count($this->equiposticket) > 0 ? 'required' : 'nullable', 'array'],
            'addequipo' => ['required', 'boolean'],
            'addadress' => ['required', 'boolean']
        ];
    }

    public function mount(Areawork $areawork)
    {
        $this->areawork = $areawork;
    }

    public function render()
    {
        $priorities = Priority::orderBy('name', 'asc')->get();
        $conditions = Condition::orderBy('name', 'asc')->get();
        $typeequipos = Typeequipo::orderBy('name', 'asc')->get();
        $marcas = Marca::orderBy('name', 'asc')->get();
        $caracteristicas = Caracteristica::orderBy('name', 'asc')->get();
        $estadoequipos = EstadoEquipoEnum::cases() ?? [];

        return view('livewire.modules.soporte.tickets.create-ticket', compact(
            'priorities',
            'conditions',
            'typeequipos',
            'marcas',
            'caracteristicas',
            'estadoequipos'
        ));
    }

    public function save()
    {
        $validateData = $this->validate();
        DB::beginTransaction();
        try {
            // dd($validateData);

            $tksaveds = [];
            $client = Client::firstOrCreate([
                'document' => $this->document
            ], [
                'name' => $this->name,
                'email' => trim($this->email),
                'user_id' => auth()->user()->id
            ]);

            $phones = array_map(function ($phone) {
                return ['phone' => $phone];
            }, $this->selectedphones);

            foreach ($phones as $phone) {
                $client->telephones()->firstOrCreate($phone);
            }

            if (count($this->equiposticket) > 0) {
                foreach ($this->equiposticket as $item) {
                    $ticket = Ticket::create([
                        'date' => now('America/Lima'),
                        'detalle' => null,
                        'total' => 0,
                        'atencion_id' => $item['atencion_id'],
                        'condition_id' => $item['condition_id'],
                        'priority_id' => $item['priority_id'],
                        'areawork_id' => $this->areawork->id,
                        'entorno_id' => $item['entorno_id'],
                        'client_id' => $client->id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                    ]);

                    if (array_key_exists('addequipo', $item) && $item['addequipo']) {
                        $equipo = $ticket->equipo()->create([
                            'modelo' => $item['modelo'],
                            'serie' => $item['serie'],
                            'descripcion' => $item['descripcion'],
                            'servicio' => $item['servicio'],
                            'stateinicial' => $item['stateinicial'],
                            'typeequipo_id' => $item['typeequipo_id'],
                            'marca_id' => $item['marca_id'],
                            'user_id' => auth()->user()->id,
                        ]);
                    }

                    if (array_key_exists('addadress', $item) && $item['addadress']) {
                        $ticket->direccion()->create([
                            'ubigeo_id' => $item['ubigeo_id'],
                            'name' => $item['direccionasistencia'],
                            'referencia' => $item['referencia'],
                            'date' => $item['dateasistencia']
                        ]);
                    }

                    if (!empty($this->contact['document'])) {
                        $ticket->contact()->create([
                            'document' => $this->contact['document'],
                            'name' => $this->contact['name']
                        ]);
                    }
                    $ticket->telephones()->createMany($phones);
                    $tksaveds[] = [
                        'serie' => $ticket->seriecompleta,
                        'detalle' => $ticket->detalle
                    ];
                }
            } else {
                $ticket = Ticket::create([
                    'date' => now('America/Lima'),
                    'detalle' => $this->servicio,
                    'total' => 0,
                    'atencion_id' => $this->atencion_id,
                    'condition_id' => $this->condition_id,
                    'priority_id' => $this->priority_id,
                    'areawork_id' => $this->areawork->id,
                    'entorno_id' => $this->entorno_id,
                    'client_id' => $client->id,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => auth()->user()->sucursal_id,
                ]);

                if ($this->addequipo) {
                    $equipo = $ticket->equipo()->create([
                        'modelo' => $this->modelo,
                        'serie' => $this->serie,
                        'descripcion' => $this->descripcion,
                        'servicio' => $this->servicio,
                        'stateinicial' => $this->stateinicial,
                        'typeequipo_id' => $this->typeequipo_id,
                        'marca_id' => $this->marca_id,
                        'user_id' => auth()->user()->id,
                    ]);
                }

                if ($this->addadress) {
                    $ticket->direccion()->create([
                        'ubigeo_id' => $this->ubigeo_id,
                        'name' => $this->direccionasistencia,
                        'referencia' => $this->referencia,
                        'date' => $this->dateasistencia
                    ]);
                }

                if (!empty($this->contact['document'])) {
                    $ticket->contact()->create([
                        'document' => $this->contact['document'],
                        'name' => $this->contact['name']
                    ]);
                }

                $ticket->telephones()->createMany($phones);
                $tksaveds[] = [
                    'serie' => $ticket->seriecompleta,
                    'detalle' => $ticket->detalle
                ];
            }

            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['areawork', 'areawork_id']);
            $this->dispatchBrowserEvent('created');
            session()->flash('saved-tickets', $tksaveds);
            return redirect()->route('admin.soporte.tickets');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedAtencionId($value)
    {
        $this->reset(['entornos', 'addequipo']);
        if (empty($value)) {
            $this->reset(['entorno_id']);
        }

        $atencion = Atencion::with('entornos')->find($value);
        $this->entornos = $atencion->entornos ?? [];
        $this->addequipo = $atencion->addequipo();
        if (count($this->entornos) == 1) {
            $entorno = $atencion->entornos->first();
            $this->entorno_id = $entorno->id;
            $this->addadress = $entorno->isDOA();
            Self::loadUbigeos();
        }
    }

    public function updatedEntornoId($value)
    {
        if (empty($value)) {
            $this->reset(['entorno_id']);
        }

        $entorno = Entorno::find($value);
        $this->addadress = $entorno->isDOA();

        if (!$this->addadress) {
            $this->reset([
                'addadress',
                'ubigeo_id',
                'direccionasistencia',
                'referencia',
                'dateasistencia'
            ]);
        }
        if (!$this->addequipo) {
            $this->reset([
                'typeequipo_id',
                'marca_id',
                'descripcion',
                'modelo',
                'serie',
                'stateinicial',
                'servicio'
            ]);
        }
        Self::loadUbigeos();
        $this->resetValidation();
    }

    function loadUbigeos()
    {
        if ($this->addadress) {
            $this->ubigeos = Ubigeo::orderBy('region', 'asc')
                ->orderBy('provincia', 'asc')
                ->orderBy('distrito', 'asc')->get();
        }
    }

    public function addequipo()
    {
        $this->servicio = toStrUppercase($this->servicio);
        $this->descripcion = toStrUppercase($this->descripcion);
        $this->modelo = toStrUppercase($this->modelo);
        $this->serie = toStrUppercase($this->serie);
        $equipoValidated = $this->validate([
            'entorno_id' => ['required', 'integer', 'min:1', 'exists:entornos,id'],
            'atencion_id' => ['required', 'integer', 'min:1', 'exists:atencions,id'],
            'priority_id' => ['required', 'integer', 'min:1', 'exists:priorities,id'],
            'condition_id' => ['required', 'integer', 'min:1', 'exists:conditions,id'],
            'typeequipo_id' => ['required', 'integer', 'min:1', 'exists:typeequipos,id'],
            'marca_id' => ['required', 'integer', 'min:1', 'exists:marcas,id'],
            'servicio' => ['required', 'string', 'min:6'],
            'descripcion' => ['required', 'string', 'min:3'],
            'modelo' => ['required', 'string', 'min:3'],
            'serie' => ['nullable', 'string', 'min:3'],
            'stateinicial' => ['required', 'integer', new Enum(EstadoEquipoEnum::class)],

            'ubigeo_id' => [$this->addadress ? 'required' : 'nullable', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccionasistencia' => [$this->addadress ? 'required' : 'nullable', 'string', 'min:6'],
            'referencia' => ['nullable', 'string', 'min:3'],
            'dateasistencia' => [$this->addadress ? 'required' : 'nullable', 'date', 'date_format:Y-m-d\TH:i', 'after_or_equal:today']
        ]);

        $priority = Priority::find($this->priority_id);
        $entorno = Entorno::find($this->entorno_id);
        $atencion = Atencion::find($this->atencion_id);
        $condition = Condition::find($this->condition_id);
        $typeequipo = Typeequipo::find($this->typeequipo_id);
        $marca = Marca::find($this->marca_id);

        $equipoValidated['addequipo'] = $this->addequipo;
        $equipoValidated['addadress'] = $this->addadress;
        $equipoValidated['priority'] = $priority->name;
        $equipoValidated['color'] = $priority->color;
        $equipoValidated['entorno'] = $entorno->name;
        $equipoValidated['atencion'] = $atencion->name;
        $equipoValidated['condition'] = $condition->name;
        $equipoValidated['typeequipo'] = $typeequipo->name;
        $equipoValidated['marca'] = $marca->name;
        $equipoValidated['estado'] = EstadoEquipoEnum::getLabel($this->stateinicial);
        $equipoValidated['lugar'] = null;
        $equipoValidated['id'] = Str::uuid();

        if ($this->addadress) {
            $ubigeo = Ubigeo::find($this->ubigeo_id);
            $equipoValidated['lugar'] =  "$ubigeo->region - $ubigeo->provincia - $ubigeo->distrito";
        }

        $this->equiposticket[] = $equipoValidated;
        $this->resetValidation();
        $this->reset(['typeequipo_id', 'marca_id', 'descripcion', 'modelo', 'serie', 'stateinicial', 'servicio']);
        if ($this->addadress) {
            $this->reset(['ubigeo_id', 'direccionasistencia', 'referencia', 'dateasistencia']);
        }
        $this->dispatchBrowserEvent('toast', toastJSON('EQUIPO AGREGADO'));
    }

    public function removeequipo($id)
    {
        $equiposticket = $this->removeFromArrayByKey($this->equiposticket, 'id', $id);
        $this->equiposticket = $equiposticket;
    }

    public function addphone($closemodal = false)
    {
        $this->phonenumber = trim($this->phonenumber);
        $this->validate([
            'phonenumber' => ['required', 'numeric', 'digits:9', 'regex:/^\d{9}$/']
        ]);

        $coincidencias = $this->existsInArrayByKey($this->telefonos, 'phone', $this->phonenumber);
        if ($coincidencias) {
            $this->addError('phonenumber', 'El número de teléfono ya está agregado.');
            return false;
        }

        $this->telefonos[] = [
            'id' => null,
            'phone' => $this->phonenumber,
            'selected' => true
        ];

        $this->selectedphones[] = $this->phonenumber;
        $this->reset('phonenumber');
        if ($closemodal) {
            $this->reset('openphone');
        }
    }

    public function removephone($value)
    {
        $this->telefonos = $this->removeFromArrayByKey($this->telefonos, 'phone', $value);
    }

    public function limpiarcliente()
    {
        $this->reset(['client_id', 'document', 'name', 'telefonos']);
    }

    public function addcontact()
    {
        $this->resetValidation();
        $this->documentcontact =  trim($this->documentcontact);
        $this->namecontact = toStrUppercase($this->namecontact);
        $validated = $this->validate([
            'documentcontact' => ['required', 'numeric', 'regex:/^\d{8}$/', new ValidateDocument],
            'namecontact' => ['required', 'string', 'min:6']
        ]);

        $exists = $this->existsInArrayByKey($this->contacts, 'document', $this->documentcontact);
        if ($exists) {
            $this->addError('documentcontact', 'El documento del contato ya existe.');
            return false;
        }

        $contact = [
            'document' => $validated['documentcontact'],
            'name' => $validated['namecontact']
        ];
        $this->contact = $contact;
        $this->contacts[] = $contact;
        $this->reset(['documentcontact', 'namecontact']);
    }

    public function confirmcontact()
    {
        $this->resetValidation();
        $this->openphone = false;
    }

    public function searchcliente()
    {
        $this->resetValidation();
        $this->document = trim($this->document);
        $this->validate(['document' => [
            'required',
            'numeric',
            new ValidateDocument,
            'regex:/^\d{8}(?:\d{3})?$/'
        ]]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
            'autosaved' =>  true,
            'savedireccions' => true,
            'searchbd' => true,
            'obtenerlista' => false,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            // dd($cliente);
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->direccion = $cliente->direccion;
                if (isset($cliente->id)) {
                    $this->client_id = $cliente->id;
                }

                if ($cliente->telefonos) {
                    $mapper = function ($phone): array {
                        return [
                            'id' => Str::uuid(),
                            'phone' => $phone->phone,
                            'selected' => false
                        ];
                    };

                    $this->telefonos = array_map($mapper, $cliente->telefonos);
                    if ($cliente->contacts) {
                        $this->contacts = array_map(function ($item) {
                            return [
                                'id' => $item->id,
                                'document' => $item->document,
                                'name' => $item->name,
                            ];
                        }, $cliente->contacts);
                    }
                }
                $this->openphone = true;

                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $mensaje =  response()->json([
                    'title' => $cliente->error . " - " . $cliente->function,
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
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

    public function searchcontact()
    {
        $this->resetValidation();
        $this->documentcontact = trim($this->documentcontact);
        $this->validate(['documentcontact' => [
            'required',
            'numeric',
            new ValidateDocument,
            'regex:/^\d{8}$/'
        ]]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->documentcontact,
            'autosaved' =>  true,
            'savedireccions' => true,
            'searchbd' => true,
            'obtenerlista' => false,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->namecontact = $cliente->name;
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $mensaje =  response()->json([
                    'title' => $cliente->error,
                    'text' => null
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
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
}
