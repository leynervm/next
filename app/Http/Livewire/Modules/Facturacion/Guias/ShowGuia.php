<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Helpers\GetClient;
use App\Models\Guia;
use App\Models\Modalidadtransporte;
use App\Models\Motivotraslado;
use App\Models\Transportdriver;
use App\Models\Transportvehiculo;
use App\Models\Ubigeo;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ShowGuia extends Component
{

    public $guia, $motivotraslado, $modalidadtransporte, $sucursal, $empresa, $client;
    public $vehiculosml = false;
    public $vehiculovacio = false;

    public $provinciasorigen = [];
    public $distritosorigen = [];
    public $provinciasdestino = [];
    public $distritosdestino = [];
    public $arrayequalremite = ['02', '04', '07'];
    public $arraydistintremite = ['01', '03', '05', '06', '09', '14', '17'];

    public $regionorigen_id, $provinciaorigen_id, $distritoorigen_id;
    public $regiondestino_id, $provinciadestino_id, $distritodestino_id;

    public $documentcomprador, $namecomprador;
    public $documentdriver, $namedriver, $lastname, $placa, $licencia;

    protected $listeners = ['deletevehiculo', 'deletedriver'];

    protected function rules()
    {
        return [
            'guia.ructransport' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
            ],
            'guia.nametransport' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'string', 'min:6',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.name' : '',
            ],
            'guia.documentdestinatario' => [
                'required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '01' && $this->guia->comprobante ? 'same:guia.comprobante.client.document' : '',
                $this->motivotraslado->code == '03' ? 'different:documentcomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ],
            'guia.namedestinatario' => [
                'required', 'string', 'min:6',
                $this->motivotraslado->code == '03' ? 'different:namecomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.name' : ''),
            ],
            'documentcomprador' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '03'),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' && $this->guia->comprobante ? 'same:client.document' : '',
                $this->motivotraslado->code == '03' ? 'different:documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : '')
            ],
            'namecomprador' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '03'),
                'string', 'min:6',
                $this->motivotraslado->code == '03' ? 'different:namedestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.name' : '')
            ],
            'guia.rucproveedor' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '02'),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->motivotraslado->code == '02' ? 'different:guia.documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : ''
            ],
            'guia.nameproveedor' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '02'),
                'string', 'min:6',
                $this->motivotraslado->code == '02' ? 'different:namedestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : ''
            ],
            'guia.anexoorigen' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '04'),
                'numeric', 'min:0', 'max:4',
                $this->motivotraslado->code == '04' ? 'different:anexodestino' : '',
            ],
            'guia.anexodestino' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '04'),
                'numeric', 'min:0', 'max:4',
                $this->motivotraslado->code == '04' ? 'different:anexoorigen' : '',
            ],

            'guia.peso' => ['required', 'numeric', 'gt:0', 'decimal:0,4',],
            'guia.packages' => ['required', 'integer', 'min:1',],
            'guia.datetraslado' => ['required', 'date'],
            'guia.note' => ['nullable', 'string', 'min:10'],
            'guia.placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],
            'regionorigen_id' => ['required', 'string', 'exists:ubigeos,departamento_inei'],
            'provinciaorigen_id' => ['required', 'string', 'exists:ubigeos,provincia_inei'],
            'distritoorigen_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'guia.ubigeoorigen_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'guia.direccionorigen' => ['required', 'string', 'min:12'],
            'regiondestino_id' => ['required', 'string', 'exists:ubigeos,departamento_inei'],
            'provinciadestino_id' => ['required', 'string', 'exists:ubigeos,provincia_inei'],
            'distritodestino_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'guia.ubigeodestino_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'guia.direcciondestino' => ['required', 'string', 'min:12',],
            'guia.indicadorvehiculosml' => ['required', 'integer', 'min:0', 'max:1'],
            'guia.indicadorvehretorvacio' => ['required', 'integer', 'min:0', 'max:1'],
            'guia.motivotraslado_id' => ['required', 'integer', 'min:1', 'exists:motivotraslados,id'],
            'guia.modalidadtransporte_id' => ['required', 'integer', 'min:1', 'exists:modalidadtransportes,id'],
            'guia.seriecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'guia.sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id']
        ];
    }

    public function mount(Guia $guia)
    {
        $this->guia = $guia;
        $this->sucursal = $guia->sucursal;
        $this->empresa = $guia->sucursal->empresa;
        $this->motivotraslado = $guia->motivotraslado;
        $this->modalidadtransporte = $guia->modalidadtransporte;

        if ($guia->comprobante) {
            $this->client = $guia->comprobante->client;
        }

        $this->vehiculosml = $guia->indicadorvehiculosml == 1 ? true : false;
        $this->vehiculovacio = $guia->indicadorvehretorvacio == 1 ? true : false;

        if ($guia->motivotraslado->code == '03') {
            $this->documentcomprador = $guia->client->document;
            $this->namecomprador = $guia->client->name;
        }


        $this->regionorigen_id = $guia->ubigeoorigen->departamento_inei;
        $this->regiondestino_id = $guia->ubigeodestino->departamento_inei;

        $this->provinciaorigen_id = $guia->ubigeoorigen->provincia_inei;
        $this->provinciadestino_id = $guia->ubigeodestino->provincia_inei;

        $this->distritoorigen_id = $guia->ubigeoorigen->id;
        $this->distritodestino_id = $guia->ubigeodestino->id;

        $this->distritosorigen = Ubigeo::select('id', 'distrito')->where('provincia_inei', $guia->ubigeoorigen->provincia_inei)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();
        $this->distritosdestino = Ubigeo::select('id', 'distrito')->where('provincia_inei', $guia->ubigeodestino->provincia_inei)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();

        $this->loadprovinciasorigen($guia->ubigeoorigen->departamento_inei);
        $this->loadprovinciasdestino($guia->ubigeodestino->departamento_inei);
    }

    public function render()
    {
        $regiones = Ubigeo::select('departamento_inei', 'region')->groupBy('departamento_inei', 'region')->orderBy('region', 'asc')->get();
        $modalidadtransportes = Modalidadtransporte::orderBy('id', 'asc')->get();
        $motivotraslados = Motivotraslado::orderBy('code', 'asc')->get();

        return view('livewire.modules.facturacion.guias.show-guia', compact('regiones', 'modalidadtransportes', 'motivotraslados'));
    }

    public function update()
    {

        $this->guia->indicadorvehiculosml = $this->vehiculosml ? 1 : 0;
        $this->guia->indicadorvehretorvacio = $this->vehiculovacio ? 1 : 0;

        $this->guia->rucproveedor = trim($this->guia->rucproveedor);
        $this->guia->nameproveedor = trim($this->guia->nameproveedor);

        $this->guia->direccionorigen = trim($this->guia->direccionorigen);
        $this->guia->ubigeoorigen_id = $this->distritoorigen_id;
        $this->guia->direcciondestino = trim($this->guia->direcciondestino);
        $this->guia->ubigeodestino_id = $this->distritodestino_id;
        $this->guia->documentdestinatario = trim($this->guia->documentdestinatario);
        $this->guia->namedestinatario = trim($this->guia->namedestinatario);
        $this->guia->placavehiculo = !$this->vehiculosml ? null : trim($this->guia->placavehiculo) ?? null;
        $this->guia->placavehiculo = empty(trim($this->guia->placavehiculo)) ? null : $this->guia->placavehiculo;

        if ($this->guia->modalidadtransporte_id) {
            $this->modalidadtransporte = Modalidadtransporte::find($this->guia->modalidadtransporte_id);
        }

        if ($this->guia->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->guia->motivotraslado_id);
            if (in_array($this->motivotraslado->code, $this->arrayequalremite)) {
                $this->guia->documentdestinatario = $this->sucursal->empresa->document;
                $this->guia->namedestinatario = $this->sucursal->empresa->name;
            }
            $this->guia->anexoorigen = $this->motivotraslado->code == '04' ? $this->sucursal->codeanexo : null;
            $this->guia->anexodestino = $this->motivotraslado->code == '04' ? $this->guia->anexodestino : null;
        }

        // if ($this->guia->motivotraslado->code != '03') {
        //     $this->guia->documentdestinatario = trim($this->guia->client->document);
        //     $this->guia->namedestinatario = trim($this->guia->client->name);
        // }

        // if ($this->modalidadtransporte->code == '01' && $this->vehiculosml == false) {
        //     $this->guia->ructransport = trim($this->guia->ructransport);
        //     $this->guia->nametransport = trim($this->guia->nametransport);
        // }

        if ($this->vehiculosml) {
            $this->reset(['documentdriver', 'namedriver', 'lastname', 'licencia']);
        }

        $this->validate();
        if ($this->vehiculosml) {
            // $this->guia->ructransport = null;
            // $this->guia->nametransport = null;
            $this->guia->transportvehiculos()->delete();
            $this->guia->transportdrivers()->delete();
        }
        $this->guia->save();
        $this->resetValidation();
        $this->dispatchBrowserEvent('updated');
    }

    public function savevehiculo()
    {
        $this->placa = trim(mb_strtoupper($this->placa, "UTF-8"));
        $this->validate([
            'placa' => [
                'required', 'string', 'min:6', 'max:8',
                Rule::unique('transportvehiculos', 'placa')
                    ->where('guia_id', $this->guia->id)
            ],
        ]);

        DB::beginTransaction();
        try {
            $principal = $this->guia->transportvehiculos()->principal()->count() == 0 ? Transportvehiculo::PRINCIPAL : Transportvehiculo::SECUNDARIO;
            $this->guia->transportvehiculos()->create([
                'placa' => $this->placa,
                'principal' => $principal
            ]);
            DB::commit();
            $this->resetValidation();
            $this->reset(['placa']);
            $this->guia->refresh();
            $mensaje = response()->json([
                'title' => 'Vehículo de transporte registrado correctamente',
                'icon' => 'success'
            ]);
            $this->dispatchBrowserEvent('toast', $mensaje->getData());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletevehiculo(Transportvehiculo $transportvehiculo)
    {
        if ($transportvehiculo->guia->codesunat == '0') {
            $mensaje = response()->json([
                'title' => 'Guía de remisión ya se encuentra enviada a SUNAT !',
                'text' => 'La guia de remision ' . $transportvehiculo->guia->seriecompleta . ' ya se encuentra notificada a SUNAT, no puede alterar el comprobante.'
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        } else {
            DB::beginTransaction();
            try {
                $transportvehiculo->delete();

                if ($this->guia->transportvehiculos()->count() > 0) {
                    if ($this->guia->transportvehiculos()->principal()->count() == 0) {
                        $this->guia->transportvehiculos()->first()->update([
                            'principal' => Transportvehiculo::PRINCIPAL
                        ]);
                    }
                }
                DB::commit();
                $this->guia->refresh();
                $this->dispatchBrowserEvent('deleted');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function savedriver()
    {
        $this->documentdriver = trim($this->documentdriver);
        $this->namedriver = trim(mb_strtoupper($this->namedriver, "UTF-8"));
        $this->lastname = trim(mb_strtoupper($this->lastname, "UTF-8"));
        $this->licencia = trim(mb_strtoupper($this->licencia, "UTF-8"));

        $this->validate([
            'documentdriver' => [
                'required', 'numeric', 'digits:8', 'regex:/^\d{8}$/',
                Rule::unique('transportdrivers', 'document')
                    ->where('guia_id', $this->guia->id)
            ],
            'namedriver' => ['required', 'string', 'min:6'],
            'lastname' => ['required', 'string', 'min:6'],
            'licencia' => ['required', 'string', 'min:9', 'max:10'],
        ]);

        DB::beginTransaction();
        try {
            $principal = $this->guia->transportdrivers()->principal()->count() == 0 ? Transportdriver::PRINCIPAL : Transportdriver::SECUNDARIO;
            $this->guia->transportdrivers()->create([
                'document' => $this->documentdriver,
                'name' => $this->namedriver,
                'lastname' => $this->lastname,
                'licencia' => $this->licencia,
                'principal' => $principal
            ]);
            DB::commit();
            $this->guia->refresh();
            $this->resetValidation();
            $this->reset(['documentdriver', 'namedriver', 'lastname']);
            $mensaje = response()->json([
                'title' => 'Vehículo de transporte registrado correctamente',
                'icon' => 'success'
            ]);
            $this->dispatchBrowserEvent('toast', $mensaje->getData());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deletedriver(Transportdriver $driver)
    {
        if ($driver->guia->codesunat == '0') {
            $mensaje = response()->json([
                'title' => 'Guía de remisión ya se encuentra enviada a SUNAT !',
                'text' => 'La guia de remision ' . $driver->guia->seriecompleta . ' ya se encuentra notificada a SUNAT, no puede alterar el comprobante.'
            ]);
            $this->dispatchBrowserEvent('validation', $mensaje->getData());
        } else {
            DB::beginTransaction();
            try {
                $driver->delete();
                if ($this->guia->transportdrivers()->count() > 0) {
                    if ($this->guia->transportdrivers()->principal()->count() == 0) {
                        $this->guia->transportdrivers()->first()->update([
                            'principal' => Transportdriver::PRINCIPAL
                        ]);
                    }
                }
                DB::commit();
                $this->guia->refresh();
                $this->dispatchBrowserEvent('deleted');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            } catch (\Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    public function getProveedor()
    {
        $this->guia->rucproveedor = trim($this->guia->rucproveedor);
        $this->validate([
            'guia.rucproveedor' => [
                'required', 'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->motivotraslado->code == '02' ? 'different:guia.documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : ''
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->guia->rucproveedor);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['guia.rucproveedor', 'guia.nameproveedor']);
                $this->guia->nameproveedor = $response->getData()->name;
            } else {
                $this->resetValidation(['guia.rucproveedor']);
                $this->addError('guia.rucproveedor', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getTransport()
    {

        $this->guia->ructransport = trim($this->guia->ructransport);
        $this->validate([
            'guia.ructransport' => [
                'required', 'numeric', 'digits:11', 'regex:/^\d{11}$/'
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->guia->ructransport);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['ructransport', 'nametransport']);
                $this->guia->nametransport = $response->getData()->name;
            } else {
                $this->resetValidation(['ructransport']);
                $this->addError('ructransport', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getDestinatario()
    {

        if ($this->guia->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->guia->motivotraslado_id);
        }
        $this->guia->documentdestinatario = trim($this->guia->documentdestinatario);
        $this->validate([
            'guia.documentdestinatario' => [
                'required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '01' && $this->guia->comprobante ? 'same:client.document' : '',
                $this->motivotraslado->code == '03' ? 'different:documentcomprador' : '',
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->guia->documentdestinatario);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['guia.documentdestinatario', 'guia.namedestinatario']);
                $this->guia->namedestinatario = $response->getData()->name;
            } else {
                $this->resetValidation(['guia.documentdestinatario']);
                $this->addError('guia.documentdestinatario', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getComprador()
    {

        $this->documentcomprador = trim($this->documentcomprador);
        $this->validate([
            'documentcomprador' => [
                'required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' && $this->guia->comprobante ? 'same:client.document' : '',
                $this->motivotraslado->code == '03' ? 'different:guia.documentdestinatario' : '',
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->documentcomprador);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['documentcomprador', 'namecomprador']);
                $this->namecomprador = $response->getData()->name;
            } else {
                $this->resetValidation(['documentcomprador']);
                $this->addError('documentcomprador', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getDriver()
    {

        $this->documentdriver = trim($this->documentdriver);
        $this->validate([
            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02'),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/'
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->documentdriver);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['documentdriver', 'namedriver']);
                $this->namedriver = $response->getData()->name;
            } else {
                $this->resetValidation(['documentdriver']);
                $this->addError('documentdriver', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function loadprovinciasorigen($value)
    {
        if ($value) {
            $this->provinciasorigen = Ubigeo::select('provincia_inei', 'provincia')->where('departamento_inei', $value)->groupBy('provincia_inei', 'provincia')->orderBy('provincia', 'asc')->get();
        }
    }

    public function updatedRegionorigenId($value)
    {
        $this->reset(['provinciasorigen', 'distritosorigen', 'provinciaorigen_id', 'distritoorigen_id']);
        $this->loadprovinciasorigen($this->regionorigen_id);
    }

    public function updatedProvinciaorigenId($value)
    {
        $this->reset(['distritosorigen', 'distritoorigen_id']);
        if ($value) {
            $this->distritosorigen = Ubigeo::select('id', 'distrito')->where('provincia_inei', $this->provinciaorigen_id)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();
        }
        $this->loadprovinciasorigen($this->regionorigen_id);
    }

    public function loadprovinciasdestino($value)
    {
        if ($value) {
            $this->provinciasdestino = Ubigeo::select('provincia_inei', 'provincia')->where('departamento_inei', $value)->groupBy('provincia_inei', 'provincia')->orderBy('provincia', 'asc')->get();
        }
    }

    public function updatedRegiondestinoId($value)
    {
        $this->reset(['provinciasdestino', 'distritosdestino', 'provinciadestino_id', 'distritodestino_id']);
        $this->loadprovinciasdestino($this->regiondestino_id);
    }

    public function updatedProvinciadestinoId($value)
    {
        $this->reset(['distritosdestino', 'distritodestino_id']);
        if ($value) {
            $this->distritosdestino = Ubigeo::select('id', 'distrito')->where('provincia_inei', $this->provinciadestino_id)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();
        }
        $this->loadprovinciasdestino($this->regiondestino_id);
    }


    public function hydrate()
    {
        $this->loadprovinciasorigen($this->regionorigen_id);
        $this->loadprovinciasdestino($this->regiondestino_id);
    }

}
