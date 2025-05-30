<?php

namespace Modules\Soporte\Http\Livewire\Orders;

use App\Models\Caracteristica;
use App\Models\Client;
use App\Models\Marca;
use App\Models\Moneda;
use App\Rules\ValidateContacto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Condition;
use Modules\Soporte\Entities\Entorno;
use Modules\Soporte\Entities\Equipo;
use Modules\Soporte\Entities\Estate;
use Modules\Soporte\Entities\Priority;
use Modules\Soporte\Entities\Ticket;

class CreateOrder extends Component
{

    public $openModalClient = false;
    public $mostrarcontacto = false;
    public $opemModalAutollenado = false;
    public $openModalContacto = false;
    public $openModalTelefono = false;

    public $area;
    public $atencion;
    public $entorno;
    public $condition;
    public $client;
    public $estate;
    public $moneda;
    public $clients = [];
    public $direccions = [];
    public $telephonesclient = [];
    public $autocomplete = [];

    public $priority_id, $entorno_id, $atencion_id,
        $condition_id, $client_id, $direccion, $detalle,
        $contact_id;

    public $equipamentrequire, $equipo_id, $marca_id, $modelo, $serie,
        $stateinicial, $descripcion;

    public $documentContact, $nameContact, $telefonoContact;

    public $ubigeolugar_id, $direccionlugar, $referencia, $datevisita;

    public $documentclient, $nameclient, $ubigeo_id, $direccionClient, $emailClient,
        $sexoClient, $nacimientoClient, $pricetype_id, $channelsale_id, $telefonoClient;

    public $mensaje;
    public $newtelefono;
    public $newdocumentcontacto, $newnamecontacto, $newtelefonocontacto;


    protected function rules()
    {
        return [
            'estate.id' => ['required', 'exists:estates,id'],
            'moneda.id' => ['required', 'exists:monedas,id'],
            'priority_id' => ['required', 'exists:priorities,id'],
            'entorno_id' => ['required', 'exists:entornos,id'],
            'atencion_id' => ['required', 'exists:atencions,id'],
            'condition_id' => ['required', 'exists:conditions,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'contact_id' => [new ValidateContacto($this->client->document)],
            'direccion' => ['required'],
            'equipo_id' => [
                'nullable', 'required_if:atencion.equipamentrequire,1', 'exists:equipos,id'
            ],
            'marca_id' => [
                'nullable', 'required_if:atencion.equipamentrequire,1', 'exists:marcas,id'
            ],
            'modelo' => ['nullable', 'required_if:atencion.equipamentrequire,1'],
            'serie' => ['nullable'],
            'stateinicial' => ['nullable', 'required_if:atencion.equipamentrequire,1'],
            'descripcion' => ['nullable', 'required_if:atencion.equipamentrequire,1'],
            'detalle' => ['required', 'string'],
            'ubigeolugar_id' => [
                'nullable', 'required_if:entorno.requiredirection,1', 'exists:ubigeos,id'
            ],
            'direccionlugar' => ['nullable', 'required_if:entorno.requiredirection,1'],
            'referencia' => ['nullable', 'required_if:entorno.requiredirection,1'],
            'datevisita' => [
                'nullable', 'required_if:entorno.requiredirection,1', 'date', 'after:' . now("America/Lima")
            ]
        ];
    }

    public function mount()
    {
        $this->atencion = new Atencion();
        $this->entorno = new Entorno();
        $this->condition = new Condition();
        $this->client = new Client();
        $this->estate = new Estate();
        $this->clients = Client::orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $prioridades = Priority::where('delete', 0)->orderBy('name', 'asc')->get();
        $conditions = Condition::where('delete', 0)->orderBy('name', 'asc')->get();
        $equipos = Equipo::where('delete', 0)->orderBy('name', 'asc')->get();
        $marcas = Marca::where('delete', 0)->orderBy('name', 'asc')->get();
        $caracteristicas = Caracteristica::where('delete', 0)->orderBy('name', 'asc')->get();
        return view('soporte::livewire.orders.create-order', compact('prioridades', 'conditions', 'equipos', 'marcas', 'caracteristicas'));
    }

    public function hydrate()
    {
        $this->clients = Client::where('delete', 0)->orderBy('name', 'asc')->get();
        $this->dispatchBrowserEvent('render-select2');
    }

    public function updatedAtencionId($value)
    {
        $this->atencion = Atencion::find($value);
        $this->equipamentrequire = $this->atencion->equipamentrequire;
    }

    public function updatedEntornoId($value)
    {
        $this->entorno = Entorno::find($value);
    }

    public function updatedClientId($value)
    {
        $this->reset(['direccion']);
        $this->client = Client::find($value);

        if (count($this->client->direccions)) {
            $this->direccion = $this->client->direccions->first()->id;
        }

        if ($this->client->nacimiento) {
            $nacimiento = Carbon::parse($this->client->nacimiento)->format("d-m");
            $hoy = Carbon::now('America/Lima')->format("d-m");

            if ($nacimiento ==  $hoy) {
                $this->mensaje = "FELÍZ CUMPLEAÑOS";
            }
        }
    }

    public function updatedConditionId($value)
    {
        $this->condition = Condition::find($value);
    }

    public function updatedDocumentclient($value)
    {
        if (strlen(trim($this->documentclient)) == 11) {
            $this->mostrarcontacto = true;
        } else {
            $this->mostrarcontacto = false;
        }
    }

    public function save()
    {

        $this->descripcion = trim($this->descripcion);
        $this->direccionlugar = trim($this->direccionlugar);
        $this->referencia = trim($this->referencia);
        $this->descripcion = trim($this->descripcion);
        $this->detalle = trim($this->detalle);
        $this->estate = Estate::where('default', 1)->first();
        $this->moneda = Moneda::where('default', 1)->first();
        $this->validate();

        $order = Ticket::create([
            'date' => now('America/Lima'),
            // 'code' => '1',
            'detalle' => $this->detalle,
            'total' => 0,
            'qr' => rand(),
            'atencion_id' => $this->atencion_id,
            'condition_id' => $this->condition_id,
            'priority_id' => $this->priority_id,
            'area_id' => $this->area->id,
            'entorno_id' => $this->entorno_id,
            'estate_id' => $this->estate->id,
            'client_id' => $this->client_id,
            'moneda_id' => $this->moneda->id,
            'user_id' => Auth::user()->id
        ]);

        if ($this->atencion->equipamentrequire == 1) {
            $order->orderequipo()->create([
                'modelo' => $this->modelo,
                'serie' => $this->serie,
                'descripcion' => $this->descripcion,
                'stateinicial' => $this->stateinicial,
                'equipo_id' => $this->equipo_id,
                'marca_id' => $this->marca_id,
                'user_id' => Auth::user()->id
            ]);
        }

        if ($this->entorno->requiredirection) {
            $order->orderdireccion()->create([
                'visita' => $this->datevisita,
                'direccion' => $this->direccionlugar,
                'referencia' => $this->referencia,
                'ubigeo_id' => $this->ubigeolugar_id,
            ]);
        }

        // $this->resetExcept(['client', 'client_id', 'direccion', '', '']);
        $this->reset(['atencion_id', 'detalle', 'condition_id', 'priority_id', 'entorno_id']);
        $this->emitTo('soporte::orders.latest-orders', 'newOrder', $order->id);
    }

    public function saveClient()
    {

        $validateData = $this->validate([
            'documentclient' => ['required', 'integer', 'numeric', 'digits_between:8,11', 'unique:clients,document'],
            'nameclient' => ['required'],
            'ubigeo_id' => ['nullable'],
            'direccionClient' => ['required'],
            'emailClient' => ['nullable', 'email'],
            'sexoClient' => ['nullable'],
            'nacimientoClient' => ['nullable'],
            'pricetype_id' => ['nullable'],
            'channelsale_id' => ['nullable'],
            'telefonoClient' => ['required'],
            'documentContact' => [new ValidateContacto($this->documentclient)],
            'nameContact' => [new ValidateContacto($this->documentclient)],
            'telefonoContact' => [new ValidateContacto($this->documentclient)],
        ]);

        $client = Client::create([
            'date' => now('America/Lima'),
            'document' => $this->documentclient,
            'name' => $this->nameclient,
            'email' => $this->emailClient,
            'nacimiento' => $this->nacimientoClient,
            'sexo' => $this->sexoClient,
            'pricetype_id' => $this->pricetype_id,
            'channelsale_id' => $this->channelsale_id,
            'user_id' => Auth::user()->id,
        ]);

        $direccion = $client->direccions()->create([
            'name' => $this->direccionClient,
            'ubigeo_id' => $this->ubigeo_id,
            'user_id' => Auth::user()->id,
        ]);

        $client->telephones()->create([
            'phone' => $this->telefonoClient,
            'user_id' => Auth::user()->id,
        ]);

        if (strlen(trim($this->documentclient)) == 11) {
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

        $this->reset([
            'openModalClient', 'mostrarcontacto', 'documentclient', 'nameclient',
            'ubigeo_id', 'direccionClient', 'emailClient', 'sexoClient',
            'nacimientoClient', 'pricetype_id', 'channelsale_id', 'telefonoClient',
            'documentContact', 'nameContact', 'telefonoContact',
        ]);

        $this->clients = Client::where('delete', 0)->orderBy('name', 'asc')->get();
        $this->client = $client;
        $this->client_id = $client->id;
        $this->direccion = $direccion->id;
        // $this->mount();
    }

    public function send_autollenado()
    {

        if (count($this->autocomplete)) {

            $descripcionEquipo = '';

            foreach ($this->autocomplete as $data => $key) {
                $title = '';
                if (!empty($key)) {
                    if ($data == "cargador") {
                        $title = "CARGADOR S/N° : ";
                    } elseif ($data == "tintas") {
                        $title = "TINTAS : ";
                    } else if ($data == "contadorpaginas") {
                        $title = "CONTADOR PÁGINAS : ";
                    } else if ($data == "toner") {
                        $title = "TONER : ";
                    }
                    $descripcionEquipo .=  $title . $key . ', ';
                }
            }

            $this->descripcion = $descripcionEquipo;
        }
        $this->opemModalAutollenado = false;
    }

    public function saveTelefono()
    {

        $this->newtelefono = trim($this->newtelefono);
        $validateData = $this->validate([
            'newtelefono' => 'required|numeric|digits_between:6,9',
            'client' => 'required'
        ]);

        if ($this->client) {
            $this->client->telephones()->create([
                'phone' => $this->newtelefono,
                'user_id' => Auth::user()->id,
            ]);
        }

        $this->reset(['newtelefono', 'openModalTelefono']);
        $this->client = Client::find($this->client_id);
    }


    public function saveContacto()
    {

        $this->newdocumentcontacto = trim($this->newdocumentcontacto);
        $this->newnamecontacto = trim($this->newnamecontacto);
        $this->newtelefonocontacto = trim($this->newtelefonocontacto);
        $validateData = $this->validate([
            'newdocumentcontacto' => 'required|numeric|digits:8',
            'newnamecontacto' => 'required|string',
            'newtelefonocontacto' => 'required|numeric|digits_between:6,9',
            'client' => 'required'
        ]);

        if ($this->client) {
            $contact = $this->client->contacts()->create([
                'document' => $this->newdocumentcontacto,
                'name' => $this->newnamecontacto,
                'user_id' => Auth::user()->id,
            ]);

            $contact->telephones()->create([
                'phone' => $this->newtelefonocontacto,
                'user_id' => Auth::user()->id,
            ]);
        }

        $this->reset(['newdocumentcontacto', 'newnamecontacto', 'newtelefonocontacto', 'openModalContacto']);
        $this->client = Client::find($this->client_id);
    }


    // public function openModalClient()
    // {
    //     $this->emitTo('soporte::orders.create-client', 'openModal');
    // }
}
