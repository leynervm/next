<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Helpers\GetClient;
use App\Models\Almacen;
use App\Models\Client;
use App\Models\Guia;
use App\Models\Modalidadtransporte;
use App\Models\Motivotraslado;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Serie;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Models\Ubigeo;
use App\Rules\ValidateStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Facturacion\Entities\Comprobante;
use Session;

class CreateGuia extends Component
{

    use WithPagination;

    public $motivotraslado, $modalidadtransporte, $sucursal, $empresa, $seriecomprobante;
    public $alterstock = false;
    public $clearaftersave = true;
    public $vehiculosml = false;
    public $provinciasorigen = [];
    public $distritosorigen = [];
    public $provinciasdestino = [];
    public $distritosdestino = [];
    public $itemsconfirm = [];
    public $items = [];
    public $series = [];
    public $placavehiculos = [];
    public $seriescarrito = [];

    public $arrayrequireruc = ['06', '17'];
    public $arrayequalremite = ['02', '04', '07'];
    public $arraydistintremite = ['01', '03', '05', '06', '09', '14', '17'];

    public $comprobante_id, $typecomprobante_id, $placavehiculo, $peso, $packages,
    $datetraslado, $note, $motivotraslado_id, $modalidadtransporte_id, $referencia;

    public $documentdestinatario, $namedestinatario;
    public $documentcomprador, $namecomprador;
    public $ructransport, $nametransport;
    public $rucproveedor, $nameproveedor;

    public $regionorigen_id, $provinciaorigen_id, $distritoorigen_id, $direccionorigen, $ubigeoorigen_id, $anexoorigen;
    public $regiondestino_id, $provinciadestino_id, $distritodestino_id, $direcciondestino, $ubigeodestino_id, $anexodestino;

    public $documentdriver, $namedriver, $lastname, $placa, $licencia;

    public $disponibles = true;
    public $almacen_id;

    public $producto_id, $cantidad, $serie_id, $searchserie;


    protected function rules()
    {
        return [
            'ructransport' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : ''
            ],
            'nametransport' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'string', 'min:6',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.name' : ''
            ],
            'placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],

            'documentdestinatario' => [
                'required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' ? 'different:documentcomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : '')
            ],
            'namedestinatario' => [
                'required', 'string', 'min:6',
                $this->motivotraslado->code == '03' ? 'different:namecomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.name' : '')
            ],

            'documentcomprador' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '03'),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
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

            'rucproveedor' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '02'),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->motivotraslado->code == '02' ? 'different:documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : ''
            ],
            'nameproveedor' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '02'),
                'string', 'min:6',
                $this->motivotraslado->code == '02' ? 'different:namedestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : ''
            ],


            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'numeric', 'digits:8', 'regex:/^\d{8}$/',
            ],
            'namedriver' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:3', 'max:255',
            ],
            'lastname' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:3', 'max:255',
            ],
            'licencia' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:9', 'max:10', 'uppercase',
            ],
            'placavehiculos' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'array', ($this->modalidadtransporte->code == '02' && $this->vehiculosml == false) ? 'min:1' : ''
            ],

            'anexoorigen' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '04'),
                'numeric', 'min:0', 'max:4',
                $this->motivotraslado->code == '04' ? 'different:anexodestino' : '',
            ],
            'anexodestino' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '04'),
                'numeric', 'min:0', 'max:4',
                $this->motivotraslado->code == '04' ? 'different:anexoorigen' : '',
            ],
            'regionorigen_id' => ['required', 'string', 'exists:ubigeos,departamento_inei'],
            'provinciaorigen_id' => ['required', 'string', 'exists:ubigeos,provincia_inei'],
            'distritoorigen_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'ubigeoorigen_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccionorigen' => ['required', 'string', 'min:12',],
            'regiondestino_id' => ['required', 'string', 'exists:ubigeos,departamento_inei'],
            'provinciadestino_id' => ['required', 'string', 'exists:ubigeos,provincia_inei'],
            'distritodestino_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'ubigeodestino_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direcciondestino' => ['required', 'string', 'min:12'],

            'peso' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'packages' => ['required', 'integer', 'min:1'],
            'datetraslado' => ['required', 'date', 'after_or_equal:today'],
            'note' => ['nullable', 'string', 'min:10'],
            'referencia' => ['nullable',
                Rule::requiredIf($this->motivotraslado->code == '01' || $this->motivotraslado->code == '03'),
                'string', 'min:6', 'max:13'],
            'vehiculosml' => ['required', 'boolean'],
            'motivotraslado_id' => ['required', 'integer', 'min:1', 'exists:motivotraslados,id'],
            'modalidadtransporte_id' => ['required', 'integer', 'min:1', 'exists:modalidadtransportes,id'],
            'typecomprobante_id' => ['required', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'comprobante_id' => ['nullable', 'integer', 'min:1', 'exists:comprobantes,id'],
            'seriecomprobante.id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'items' => ['required', 'array', 'min:1']
        ];
    }

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->empresa = $sucursal->empresa;
        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->seriecomprobante = new Seriecomprobante();
        $this->regionorigen_id = $sucursal->ubigeo->departamento_inei;
        $this->loadprovinciasorigen($this->regionorigen_id);
        $this->provinciaorigen_id = $sucursal->ubigeo->provincia_inei;
        $this->distritoorigen_id = $sucursal->ubigeo_id;
        $this->direccionorigen = $sucursal->direccion;
        $this->anexoorigen = $sucursal->codeanexo;
        $this->distritosorigen = Ubigeo::select('id', 'distrito')->where('provincia_inei', $this->provinciaorigen_id)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();

        $carritoSesion = Session::get('carguias', []);
        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }

        $this->seriescarrito = $this->getseriecarrito($carritoSesion);

        if ($sucursal->almacens()->exists()) {
            if ($sucursal->almacens()->count() == 1) {
                $this->almacen_id = $sucursal->almacens()->first()->pivot->almacen_id ?? null;
            } else {
                $this->almacen_id = auth()->user()->sucursalDefault()->first()->pivot->almacen_id ?? null;
            }
        }
    }

    public function render()
    {

        $regiones = Ubigeo::select('departamento_inei', 'region')
            ->groupBy('departamento_inei', 'region')
            ->orderBy('region', 'asc')
            ->get();
        $modalidadtransportes = Modalidadtransporte::orderBy('id', 'asc')->get();
        $motivotraslados = Motivotraslado::orderBy('code', 'asc')->get();
        $typecomprobantes = Typecomprobante::whereIn('code', ['09', '13', 'GR'])
            ->orderBy('code', 'asc')
            ->get();

        $almacen = [];

        if ($this->almacen_id) {
            $almacen[] = $this->almacen_id;
        }

        $productos = Producto::withWhereHas('almacens', function ($query) use ($almacen) {
            $query->whereIn('almacens.id', $almacen);
            if ($this->disponibles) {
                $query->where('cantidad', '>', 0);
            }
        });

        $productos = $productos->orderBy('name', 'asc')->paginate();
        $almacens = Almacen::orderBy('name', 'asc')->get();
        $carrito = Session::get('carguias', []);

        if (!is_array($carrito)) {
            $carrito = json_decode($carrito);
        }

        return view('livewire.modules.facturacion.guias.create-guia', compact('regiones', 'modalidadtransportes', 'motivotraslados', 'typecomprobantes', 'productos', 'almacens', 'carrito'));
    }

    public function save()
    {

        $carrito = Session::get('carguias', []);
        $this->comprobante_id = null;
        $this->referencia = trim($this->referencia);
        $this->rucproveedor = trim($this->rucproveedor);
        $this->nameproveedor = trim($this->nameproveedor);
        $this->ubigeoorigen_id = $this->distritoorigen_id;
        $this->ubigeodestino_id = $this->distritodestino_id;
        $this->typecomprobante_id = empty($this->typecomprobante_id) ? null : $this->typecomprobante_id;
        $this->items = (!is_array($carrito)) ? (json_decode($carrito, true)) : $carrito;

        if ($this->modalidadtransporte_id) {
            $this->modalidadtransporte = Modalidadtransporte::find($this->modalidadtransporte_id);
        }

        if ($this->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
            if (in_array($this->motivotraslado->code, $this->arrayequalremite)) {
                $this->documentdestinatario = $this->sucursal->empresa->document;
                $this->namedestinatario = $this->sucursal->empresa->name;
            }
            $this->anexoorigen = $this->motivotraslado->code == '04' ? $this->sucursal->codeanexo : null;
            $this->anexodestino = $this->motivotraslado->code == '04' ? $this->anexodestino : null;
        }

        if ($this->motivotraslado->code == '01' || $this->motivotraslado->code == '03') {
            $comprobante = Comprobante::where('seriecompleta', $this->referencia)
                ->where('sucursal_id', $this->sucursal->id)->first();
            $this->comprobante_id = $comprobante->id ?? null;
        }

        if ($this->vehiculosml) {
            $this->reset(['placavehiculos', 'documentdriver', 'namedriver', 'lastname', 'licencia']);
        }


        $this->seriecomprobante = $this->sucursal->seriecomprobantes()
            ->where('typecomprobante_id', $this->typecomprobante_id)->first() ?? null;
        $validatedData = $this->validate();
        // dd($validatedData);

        DB::beginTransaction();
        try {

            $documentclient = $this->motivotraslado->code == '03' ? $this->documentcomprador : $this->documentdestinatario;
            $client = Client::where('document', $documentclient)->first();

            if (!$client) {
                $client = Client::create([
                    'document' => $documentclient,
                    'name' => $this->motivotraslado->code == '03' ? $this->namecomprador : $this->namedestinatario,
                    'sexo' => strlen(trim($documentclient)) == 11 ? 'E' : null,
                    'pricetype_id' => Pricetype::DefaultPricetype()->first()->id ?? null,
                ]);
            }

            // $client->direccions()->updateOrCreate([
            //     'name' => $this->direccion,
            // ]);

            $contador = $this->seriecomprobante->contador + 1;
            $guia = Guia::create([
                'seriecompleta' => $this->seriecomprobante->serie . '-' . $contador,
                'date' => Carbon::now('America/Lima'),
                'expire' => Carbon::now('America/Lima')->format('Y-m-d'),
                'code' => $this->seriecomprobante->typecomprobante->code,
                'datetraslado' => $this->datetraslado,
                'ructransport' => $this->ructransport,
                'nametransport' => $this->nametransport,
                'placavehiculo' => $this->placavehiculo,
                'documentdestinatario' => $this->documentdestinatario,
                'namedestinatario' => $this->namedestinatario,
                'peso' => $this->peso,
                'unit' => 'KGM',
                'packages' => $this->packages,
                'documentcomprador' => $this->documentcomprador,
                'namecomprador' => $this->namecomprador,
                'rucproveedor' => $this->motivotraslado->code == '02' ? $this->rucproveedor : null,
                'nameproveedor' => $this->motivotraslado->code == '02' ? $this->nameproveedor : null,
                'direccionorigen' => $this->direccionorigen,
                'anexoorigen' => $this->anexoorigen ?? '0',
                'direcciondestino' => $this->direcciondestino,
                'anexodestino' => $this->anexodestino ?? '0',
                'regionorigen_id' => $this->regionorigen_id,
                'note' => $this->note,
                'indicadorvehiculosml' => $this->vehiculosml ? 1 : 0,
                'indicadorvehretorvacio' => 0,
                'indicadorvehretorenvacios' => 0,
                'motivotraslado_id' => $this->motivotraslado_id,
                'modalidadtransporte_id' => $this->modalidadtransporte_id,
                'ubigeoorigen_id' => $this->ubigeoorigen_id,
                'ubigeodestino_id' => $this->ubigeodestino_id,
                'client_id' => $client->id,
                'seriecomprobante_id' => $this->seriecomprobante->id,
                'comprobante_id' => $this->comprobante_id,
                'sucursal_id' => $this->sucursal->id,
                'user_id' => auth()->user()->id,
            ]);

            if ($this->modalidadtransporte->code == '02' && $this->vehiculosml == false) {
                $guia->transportdrivers()->create([
                    'document' => $this->documentdriver,
                    'name' => $this->namedriver,
                    'lastname' => $this->lastname,
                    'licencia' => $this->licencia,
                    'principal' => 1
                ]);

                $vehiculos = response()->json($this->placavehiculos)->getData();
                if (count($this->placavehiculos) > 0) {
                    foreach ($vehiculos as $vehiculo) {
                        $guia->transportvehiculos()->create([
                            'placa' => $vehiculo->placa,
                            'principal' => $vehiculo->principal,
                        ]);
                    }
                }
            }

            $carrito = (!is_array($carrito)) ? (json_decode($carrito)) : $carrito;
            foreach ($carrito as $item) {
                $tvitem = $guia->tvitems()->create([
                    'date' => now('America/Lima'),
                    'cantidad' => $item->cantidad,
                    'pricebuy' => 0,
                    'price' => 0,
                    'igv' => 0,
                    'subtotaligv' => 0,
                    'subtotal' => 0,
                    'total' => 0,
                    'status' => 0,
                    'increment' => 0,
                    'alterstock' => $item->alterstock,
                    'almacen_id' => $item->almacen_id,
                    'producto_id' => $item->producto_id,
                    'user_id' => auth()->user()->id
                ]);

                if (count($item->series) > 0) {
                    foreach ($item->series as $ser) {
                        $tvitem->itemseries()->create([
                            'date' => now('America/Lima'),
                            'status' => 0,
                            'serie_id' => $ser->id,
                            'user_id' => auth()->user()->id,
                        ]);

                        if ($item->alterstock) {
                            $ser->serie()->update([
                                'status' => 2,
                                'dateout' => now('America/Lima')
                            ]);
                        }
                    }
                }

                if ($item->alterstock) {
                    $producto = Producto::with('almacens')->find($item->producto_id);
                    $stock = $producto->almacens->find($item->almacen_id)->pivot->cantidad;

                    if ($stock && $stock > 0) {
                        if ($stock - $item->cantidad < 0) {
                            $this->addError('items', 'Cantidad del producto ' . $producto->name . ' supera el stock.');
                            return false;
                        } else {
                            $producto->almacens()->updateExistingPivot($item->almacen_id, [
                                'cantidad' => formatDecimalOrInteger($stock) - $item->cantidad,
                            ]);
                        }
                    } else {
                        $this->addError('items', 'Stock del producto ' . $producto->name . ' no disponible.');
                        return false;
                    }
                }
            }

            $this->seriecomprobante->contador = $contador;
            $this->seriecomprobante->save();
            DB::commit();
            Session::forget('carguias');
            $this->resetValidation();
            $this->resetExcept('modalidadtransporte', 'motivotraslado', 'sucursal', 'empresa', 'seriecomprobante');
            $this->dispatchBrowserEvent('created');
            return redirect()->route('admin.facturacion.guias.show', $guia);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function searchreferencia()
    {
        $this->referencia = trim($this->referencia);
        $this->reset(['itemsconfirm']);
        $this->validate([
            'referencia' => ['required', 'string', 'min:6', 'max:13']
        ]);

        if ($this->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
        }

        $comprobante = Comprobante::with('facturable')->where('seriecompleta', $this->referencia)
            ->where('sucursal_id', $this->sucursal->id)->withTrashed()->first();
        if ($comprobante) {
            if (is_null($comprobante->deleted_at)) {
                if ($comprobante->facturable instanceof Venta) {
                    $this->itemsconfirm = $comprobante->facturable->tvitems()->with('producto.unit', 'almacen', 'itemseries.serie')->get();
                    if (count($this->itemsconfirm) > 0) {
                        // dd($this->itemsconfirm);
                        if ($this->motivotraslado->code == '03') {
                            $this->documentcomprador = $comprobante->client->document;
                            $this->namecomprador = $comprobante->client->name;
                        } else {
                            $this->documentdestinatario = $comprobante->client->document;
                            $this->namedestinatario = $comprobante->client->name;
                        }

                        $this->emit('confirmaritemsguia');
                    } else {
                        $this->addError('referencia', 'No se encontraron items del comprobante.');
                    }
                } else {
                    $this->addError('referencia', 'No es instancia de una venta.');
                }
            } else {
                $this->addError('referencia', 'El comprobante se encuentra anulado.');
            }
        } else {
            $this->addError('referencia', 'No se encontraron resultados.');
        }
    }

    public function confirmaradditemsguia()
    {

        $carritoSesion = Session::get('carguias', []);
        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }
        $i = 1;
        foreach ($this->itemsconfirm as $item) {
            $id = rand();
            $nuevaSerie = [];

            if (count($item->itemseries) > 0) {
                foreach ($item->itemseries as $serie) {
                    $serie = [
                        'id' => $serie->serie->id,
                        'serie' => $serie->serie->serie
                    ];
                    $nuevaSerie[] = $serie;
                }
            }

            $nuevoItem = [
                'id' => $id,
                'item' => $i,
                'producto_id' => $item->producto_id,
                'producto' => $item->producto->name,
                'cantidad' => formatDecimalOrInteger($item->cantidad, 0),
                'sucursal_id' => $this->sucursal->id,
                'user_id' => auth()->user()->id,
                'almacen_id' => $item->almacen_id,
                'almacen' => $item->almacen->name,
                'unit' => $item->producto->unit->name,
                'alterstock' => 0,
                // 'series' => [],
            ];

            $nuevoItem['series'] = $nuevaSerie;
            $carritoSesion[$id] = $nuevoItem;
            $i++;
            // dd($nuevoItem);
        }

        $carritoJSON = response()->json($carritoSesion)->getContent();
        Session::put('carguias', $carritoJSON);
    }

    public function addtoguia()
    {

        $this->alterstock = $this->alterstock ? 1 : 0;
        $this->validate([
            'producto_id' => ['required', 'integer', 'min:1'],
            'almacen_id' => ['required', 'integer', 'min:1'],
            'cantidad' => [
                'nullable',
                Rule::requiredIf(count($this->series) == 0),
                'integer', 'min:1',
                $this->alterstock ? new ValidateStock($this->producto_id, $this->almacen_id) : ''
            ],
            'serie_id' => [
                'nullable',
                Rule::requiredIf(count($this->series) > 0),
                'integer', 'min:1'
            ],
            'alterstock' => ['required', 'integer', 'min:0', 'max:1'],
        ]);

        $producto = Producto::find($this->producto_id);
        $almacen = Almacen::find($this->almacen_id);


        if ($this->alterstock) {
            $stock = $producto->almacens->find($this->almacen_id)->pivot->cantidad;
            if ($stock && $stock > 0) {
                if (($stock - $this->cantidad) < 0) {
                    $this->addError('cantidad', 'Cantidad supera el stock');
                    return false;
                }
            }
        }

        $nuevaSerie = [];
        if ($this->serie_id) {
            $this->cantidad = 1;
            $serie = Serie::find($this->serie_id);
            $nuevaSerie = [
                'id' => $this->serie_id,
                'serie' => $serie->serie
            ];
        }

        // $series = response()->json($nuevaSerie)->getData();
        $carritoSesion = Session::get('carguias', []);

        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }

        $id = rand();
        $nuevoItem = [
            'id' => $id,
            'item' => count($carritoSesion) + 1,
            'producto_id' => $this->producto_id,
            'producto' => $producto->name,
            'cantidad' => $this->cantidad,
            'sucursal_id' => $this->sucursal->id,
            'user_id' => auth()->user()->id,
            'almacen_id' => $this->almacen_id,
            'almacen' => $almacen->name,
            'unit' => $producto->unit->name,
            'alterstock' => $this->alterstock,
            // 'series' => $nuevaSerie,
        ];

        $seriescarrito = $this->getseriecarrito($carritoSesion);
        $productExists = false;
        foreach ($carritoSesion as $key => $item) {
            if (
                $item['producto_id'] == $nuevoItem['producto_id'] && $item['almacen_id'] == $nuevoItem['almacen_id']
                && $item['sucursal_id'] == $nuevoItem['sucursal_id'] && $item['alterstock'] == $nuevoItem['alterstock']
            ) {

                $productExists = true;
                $carritoSesion[$key]['cantidad'] += $nuevoItem['cantidad'];

                if (count($nuevaSerie) > 0) {
                    if (count($carritoSesion[$key]['series']) > 0) {
                        $serieExistente = in_array($nuevaSerie['id'], $seriescarrito);
                        if ($serieExistente) {
                            $this->addError('serie_id', 'La serie ya se encuentra agregada a la guía');
                            return false;
                            // die();
                        } else {
                            $carritoSesion[$key]['series'][] = $nuevaSerie;
                        }
                    } else {
                        $carritoSesion[$key]['series'][] = $nuevaSerie;
                    }
                }
                // break;
            }
        }

        if (!$productExists) {
            count($nuevaSerie) > 0 ? $nuevoItem['series'][] = $nuevaSerie : $nuevoItem['series'] = $nuevaSerie;
            $carritoSesion[$id] = $nuevoItem;
        }

        // dd($carritoSesion);

        $carritoJSON = response()->json($carritoSesion)->getContent();
        Session::put('carguias', $carritoJSON);

        if ($this->clearaftersave) {
            $this->reset(['series', 'serie_id', 'producto_id', 'almacen_id', 'alterstock', 'cantidad']);
        } else {
            $this->reset(['series', 'serie_id', 'cantidad']);
        }

        $seriescarrito = $this->getseriecarrito($carritoSesion);
        $this->loadseries();
    }

    public function addplacavehiculo()
    {
        $this->placa = trim($this->placa);
        $this->validate([
            'placa' => ['required', 'string', 'min:7', 'max:8', 'uppercase']
        ]);

        $existPlaca = false;
        $existPincipal = false;
        foreach ($this->placavehiculos as $item => $value) {
            if ($this->placa == $item) {
                $existPlaca = true;
                break;
            }
            if ($value['principal'] == 1) {
                $existPincipal = true;
            }
        }

        if ($existPlaca) {
            $this->addError('placa', 'placa del vehículo ya se encuentra agregado');
        } else {
            $principal = $existPincipal ? 0 : 1;
            $this->placavehiculos[$this->placa] = [
                'placa' => $this->placa,
                'principal' => $principal
            ];
        }
    }

    public function deleteplacavehiculo($id)
    {
        unset($this->placavehiculos[$id]);
    }

    public function getseriecarrito($carrito)
    {
        $arraySeries = [];

        if (count($carrito) > 0) {
            foreach ($carrito as $key => $item) {
                if (count($carrito[$key]['series']) > 0) {
                    foreach ($carrito[$key]['series'] as $serie) {
                        array_push($arraySeries, $serie['id']);
                    }
                }
            }
        }

        return $arraySeries;
    }

    public function delete($id)
    {
        $carrito = Session::pull('carguias');
        if (!is_array($carrito)) {
            $carrito = json_decode($carrito, true);
            unset($carrito[$id]);
        }

        $carritoJSON = response()->json($carrito)->getContent();
        Session::put('carguias', $carritoJSON);
        $this->loadseries();
    }

    public function deleteserie($idItem, $idSerie)
    {

        $carritoSesion = Session::pull('carguias');
        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }

        $existSerie = false;
        $arrayseries = $carritoSesion[$idItem]['series'] ?? [];

        if (count($arrayseries) > 0) {
            foreach ($arrayseries as $key => $serie) {
                if ($serie['id'] == $idSerie) {
                    $existSerie = true;
                    unset($arrayseries[$key]);
                    // unset($carritoSesion[$idItem]['series'][$key]);
                }
            }

            // dd($arrayseries);
            if ($existSerie) {
                $carritoSesion[$idItem]['series'] = [];
                foreach ($arrayseries as $serie) {
                    $carritoSesion[$idItem]['series'][] = $serie;
                }

                $carritoSesion[$idItem]['cantidad'] -= 1;
            }
        }

        // dd($carritoSesion[$idItem]);

        $carritoJSON = response()->json($carritoSesion)->getContent();
        Session::put('carguias', $carritoJSON);
        $this->loadseries();
    }

    public function updatedProductoId()
    {
        $this->loadseries();
    }

    public function updatedAlmacenId()
    {
        $this->loadseries();
    }

    public function loadseries()
    {
        $this->reset(['series', 'serie_id']);
        $carritoSesion = Session::get('carguias', []);
        if (!is_array($carritoSesion)) {
            $carritoSesion = json_decode($carritoSesion, true);
        }
        $seriescarrito = $this->getseriecarrito($carritoSesion);
        $this->seriescarrito = $seriescarrito;

        if ($this->producto_id && $this->almacen_id) {
            $this->series = Serie::disponibles()->where('almacen_id', $this->almacen_id)
                ->where('producto_id', $this->producto_id)
                ->whereNotIn('id', $seriescarrito)->get();
        }
    }

    public function getProveedor()
    {
        $this->rucproveedor = trim($this->rucproveedor);
        $this->validate([
            'rucproveedor' => [
                'required',
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->motivotraslado->code == '02' ? 'different:documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : ''
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->rucproveedor);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['rucproveedor', 'nameproveedor']);
                $this->nameproveedor = $response->getData()->name;
            } else {
                $this->resetValidation(['rucproveedor']);
                $this->addError('rucproveedor', $response->getData()->message);
            }
        } else {
            dd($response);
        }
    }

    public function getTransport()
    {

        $this->ructransport = trim($this->ructransport);
        $this->validate([
            'ructransport' => [
                'required', 'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : ''
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->ructransport);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['ructransport', 'nametransport']);
                $this->nametransport = $response->getData()->name;
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

        $this->documentdestinatario = trim($this->documentdestinatario);
        $this->validate([
            'documentdestinatario' => [
                'required',
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' ? 'different:documentcomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : '')
            ],
        ]);


        $client = new GetClient();
        $response = $client->getClient($this->documentdestinatario);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['documentdestinatario', 'namedestinatario']);
                $this->namedestinatario = $response->getData()->name;
            } else {
                $this->resetValidation(['documentdestinatario']);
                $this->addError('documentdestinatario', $response->getData()->message);
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
                $this->motivotraslado->code == '03' ? 'different:documentdestinatario' : '',
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
        // $this->loadprovinciasdestino($this->regiondestino_id);
    }

    public function updatedProvinciaorigenId($value)
    {
        $this->reset(['distritosorigen', 'distritoorigen_id']);
        if ($value) {
            $this->distritosorigen = Ubigeo::select('id', 'distrito')->where('provincia_inei', $this->provinciaorigen_id)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();
        }
        $this->loadprovinciasorigen($this->regionorigen_id);
        // $this->loadprovinciasdestino($this->regiondestino_id);
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
        // $this->loadprovinciasorigen($this->regionorigen_id);
    }

    public function updatedProvinciadestinoId($value)
    {
        $this->reset(['distritosdestino', 'distritodestino_id']);
        if ($value) {
            $this->distritosdestino = Ubigeo::select('id', 'distrito')->where('provincia_inei', $this->provinciadestino_id)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();
        }
        $this->loadprovinciasdestino($this->regiondestino_id);
        // $this->loadprovinciasorigen($this->regionorigen_id);
    }

    public function hydrate()
    {
        $this->loadprovinciasorigen($this->regionorigen_id);
        $this->loadprovinciasdestino($this->regiondestino_id);
        $this->dispatchBrowserEvent('renderselect2');
    }
}
