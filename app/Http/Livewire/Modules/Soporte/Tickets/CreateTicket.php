<?php

namespace App\Http\Livewire\Modules\Soporte\Tickets;

use App\Models\Areawork;
use App\Models\Caracteristica;
use App\Models\Marca;
use App\Rules\ValidateDocument;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Condition;
use Modules\Soporte\Entities\Priority;
use Modules\Soporte\Entities\Typeequipo;
use App\Traits\ManageArrayTrait;

class CreateTicket extends Component
{

    use ManageArrayTrait;

    public $areawork;
    public $priority_id, $areawork_id, $entorno_id, $atencion_id, $condition_id, $addequipo = false;
    public $client_id, $document, $name, $direccion;
    public $entornos = [], $telefonos = [], $contacts = [];

    public $openphone = false, $opencontact = false;
    public $phonenumber;

    protected function rules()
    {
        return [
            'areawork.id' => ['required', 'exists:areaworks,id'],
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
        return view('livewire.modules.soporte.tickets.create-ticket', compact(
            'priorities',
            'conditions',
            'typeequipos',
            'marcas',
            'caracteristicas'
        ));
    }

    public function save()
    {
        $this->validate();
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
            $this->entorno_id = $atencion->entornos->first()->id;
        }
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
            'phone' => $this->phonenumber
        ];

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
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->direccion = $cliente->direccion;
                if ($cliente->telefonos) {
                    $mapper = function ($phone): array {
                        return [
                            'id' => $phone->id,
                            'phone' => $phone->phone
                        ];
                    };

                    $this->telefonos = array_map($mapper, $cliente->telefonos);
                    if ($cliente->contacts) {
                        $this->contacts = array_map(function ($item) {
                            return [
                                'id' => $item->id,
                                'document' => $item->document,
                                'name' => $item->name,
                                'telefonos' => array_map(function ($phone) {
                                    return [
                                        'id' => $phone->id,
                                        'phone' => $phone->phone
                                    ];
                                }, $item->telefonos ?? [])
                            ];
                        }, $cliente->contacts);
                    }
                }
                if (isset($cliente->id)) {
                    $this->client_id = $cliente->id;
                }
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
