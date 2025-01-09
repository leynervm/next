<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Models\Almacen;
use App\Models\Carshoop;
use App\Models\Carshoopserie;
use App\Models\Client;
use App\Models\Guia;
use App\Models\Kardex;
use App\Models\Modalidadtransporte;
use App\Models\Motivotraslado;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Serie;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Tvitem;
use App\Models\Ubigeo;
use App\Rules\ValidateStock;
use App\Traits\KardexTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Facturacion\Entities\Comprobante;
use Nwidart\Modules\Facades\Module;

class CreateGuia extends Component
{

    use WithPagination;
    use KardexTrait;
    use AuthorizesRequests;

    public $local = false;

    public $motivotraslado, $modalidadtransporte, $sucursal, $empresa, $seriecomprobante;
    public $clearaftersave = true;
    public $vehiculosml = false;
    public $vehiculovacio = false;
    public $itemsconfirm = [];
    public $items = [];
    public $series = [];
    public $placavehiculos = [];
    public $productos = [];

    public $arrayrequireruc = ['06', '17'];
    public $arrayequalremite = ['02', '04', '07'];
    public $arraydistintremite = ['01', '03', '05', '06', '09', '14', '17'];

    public $comprobante_id, $seriecomprobante_id, $placavehiculo, $peso, $packages,
        $datetraslado, $note, $motivotraslado_id, $modalidadtransporte_id, $referencia;

    public $documentdestinatario, $namedestinatario;
    public $documentcomprador, $namecomprador;
    public $ructransport, $nametransport;
    public $rucproveedor, $nameproveedor;

    public $direccionorigen, $ubigeoorigen_id, $anexoorigen;
    public $direcciondestino, $ubigeodestino_id, $anexodestino;

    public $documentdriver, $namedriver, $lastname, $placa, $licencia;

    public $disponibles = true;
    public $alterstock, $almacen_id, $producto_id, $cantidad, $serie_id, $searchserie;
    public $mode = '0';
    public $sincronizecpe = false;

    protected function rules()
    {
        return [
            'ructransport' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : ''
            ],
            'nametransport' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'string',
                'min:6',
                $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.name' : ''
            ],
            'placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],

            'documentdestinatario' => [
                'required',
                'numeric',
                $this->motivotraslado->code == '06' ? 'regex:/^\d{11}$/' : 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' ? 'different:documentcomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : '')
            ],
            'namedestinatario' => [
                'required',
                'string',
                'min:6',
                $this->motivotraslado->code == '03' ? 'different:namecomprador' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.name' : '')
            ],

            'documentcomprador' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '03' || $this->motivotraslado->code == '13'),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' || $this->motivotraslado->code == '13' ? 'different:documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : '')
            ],
            'namecomprador' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '03' || $this->motivotraslado->code == '13'),
                'string',
                'min:6',
                $this->motivotraslado->code == '03' || $this->motivotraslado->code == '13' ? 'different:namedestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.name' : '')
            ],

            'rucproveedor' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '02' || $this->motivotraslado->code == '13'),
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->motivotraslado->code == '02' ? 'different:documentdestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : ''
            ],
            'nameproveedor' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '02' || $this->motivotraslado->code == '13'),
                'string',
                'min:6',
                $this->motivotraslado->code == '02' ? 'different:namedestinatario' : '',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.name' : ''
            ],

            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'numeric',
                'digits:8',
                'regex:/^\d{8}$/',
            ],
            'namedriver' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:3',
                'max:255',
            ],
            'lastname' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:3',
                'max:255',
            ],
            'licencia' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string',
                'min:9',
                'max:10',
                'uppercase',
            ],
            'placavehiculos' => [
                'nullable',
                Rule::requiredIf($this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'array',
                ($this->modalidadtransporte->code == '02' && $this->vehiculosml == false) ? 'min:1' : ''
            ],

            'anexoorigen' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '04'),
                'numeric',
                'min:0',
                'max:4',
                $this->motivotraslado->code == '04' ? 'different:anexodestino' : '',
            ],
            'anexodestino' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '04'),
                'numeric',
                'min:0',
                'max:4',
                $this->motivotraslado->code == '04' ? 'different:anexoorigen' : '',
            ],
            'ubigeoorigen_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direccionorigen' => ['required', 'string', 'min:3',],
            'ubigeodestino_id' => ['required', 'integer', 'min:1', 'exists:ubigeos,id'],
            'direcciondestino' => ['required', 'string', 'min:3'],

            'peso' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'packages' => ['nullable', Rule::requiredIf($this->motivotraslado->code == '08' || $this->motivotraslado->code == '09'), 'integer', 'min:1'],
            'datetraslado' => ['required', 'date', 'after_or_equal:today'],
            'note' => ['nullable', Rule::requiredIf($this->motivotraslado->code == '13'), 'string', 'min:3', 'max:100'],
            'referencia' => [
                'nullable',
                Rule::requiredIf($this->motivotraslado->code == '01' || $this->motivotraslado->code == '03'),
                'string',
                'min:6',
                'max:13'
            ],
            'vehiculosml' => ['required', 'boolean'],
            'motivotraslado_id' => ['required', 'integer', 'min:1', 'exists:motivotraslados,id'],
            'modalidadtransporte_id' => ['required', 'integer', 'min:1', 'exists:modalidadtransportes,id'],
            'seriecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'comprobante_id' => ['nullable', 'integer', 'min:1', 'exists:comprobantes,id'],
            'seriecomprobante.id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'items' => ['required', 'array', 'min:1']
        ];
    }

    protected $messages = [
        'placa.uppercase' => 'El campo placa debe contener letras en mayusculas.',
    ];

    protected $validationAttributes = [
        'seriecomprobante_id'   => 'tipo guía',
        'items'                 =>   'items de guia',

    ];

    public function mount(Sucursal $sucursal)
    {
        $this->sucursal = $sucursal;
        $this->empresa = $sucursal->empresa;
        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->seriecomprobante = new Seriecomprobante();
        $this->ubigeoorigen_id = $sucursal->ubigeo_id;
        $this->direccionorigen = $sucursal->direccion;
        $this->anexoorigen = $sucursal->codeanexo;
    }

    public function render()
    {

        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')->orderBy('distrito', 'asc')->get();
        $modalidadtransportes = Modalidadtransporte::orderBy('id', 'asc')->get();
        $motivotraslados = Motivotraslado::orderBy('code', 'asc');
        if ($this->seriecomprobante_id) {
            $this->seriecomprobante = Seriecomprobante::with('typecomprobante')->find($this->seriecomprobante_id);
            $motivotraslados->where('typecomprobante_id', $this->seriecomprobante->typecomprobante_id);
        }
        $motivotraslados = $motivotraslados->get();
        $seriecomprobantes = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                $query->whereIn('code', ['09']);
            });

        if (!Module::isEnabled('Facturacion')) {
            $seriecomprobantes = $seriecomprobantes->default();
        }

        $seriecomprobantes = $seriecomprobantes->orderBy('code', 'asc')->get();
        $almacens = $this->sucursal->almacens()->orderBy('name', 'asc')->get();

        $carshoops = Carshoop::with(['carshoopseries', 'almacen', 'producto' => function ($query) {
            $query->addSelect(['image' => function ($q) {
                $q->select('url')->from('images')
                    ->whereColumn('images.imageable_id', 'productos.id')
                    ->where('images.imageable_type', Producto::class)
                    ->orderBy('default', 'desc')->limit(1);
            }])->with(['category', 'subcategory'])->visibles();
        }])->guias()->whereNull('moneda_id')->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)->orderBy('id', 'asc')->paginate();

        return view('livewire.modules.facturacion.guias.create-guia', compact('modalidadtransportes', 'motivotraslados', 'seriecomprobantes', 'almacens', 'ubigeos', 'carshoops'));
    }

    public function save()
    {

        $this->authorize('admin.facturacion.guias.create');
        $this->referencia = trim($this->referencia);
        $this->rucproveedor = trim($this->rucproveedor);
        $this->nameproveedor = trim($this->nameproveedor);
        $this->seriecomprobante_id = empty($this->seriecomprobante_id) ? null : $this->seriecomprobante_id;
        $this->items = Carshoop::with(['carshoopseries', 'producto', 'almacen'])->guias()
            ->whereNull('moneda_id')->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)->orderBy('date', 'asc')->get();

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

        // if ($this->motivotraslado->code == '01' || $this->motivotraslado->code == '03') {
        //     if ($this->sincronizecpe) {
        //         $comprobante = Comprobante::where('seriecompleta', $this->referencia)
        //             ->where('sucursal_id', $this->sucursal->id)->first();
        //         $this->comprobante_id = $comprobante->id ?? null;
        //     }
        // }

        if ($this->vehiculosml) {
            $this->reset(['placavehiculos', 'documentdriver', 'namedriver', 'lastname', 'licencia']);
        }

        if ($this->seriecomprobante_id) {
            $this->seriecomprobante = Seriecomprobante::find($this->seriecomprobante_id);
        }

        $validatedData = $this->validate();
        DB::beginTransaction();
        try {

            $documentclient = $this->motivotraslado->code == '03' || $this->motivotraslado->code == '13' ? $this->documentcomprador : $this->documentdestinatario;
            $client = Client::where('document', $documentclient)->first();

            if (!$client) {
                $client = Client::create([
                    'document' => $documentclient,
                    'name' => $this->motivotraslado->code == '03' || $this->motivotraslado->code == '13' ? $this->namecomprador : $this->namedestinatario,
                    'sexo' => strlen(trim($documentclient)) == 11 ? 'E' : null,
                    'pricetype_id' => $this->empresa->usarLista() ? Pricetype::default()->first()->id ?? null : null,
                ]);
            }

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
                'rucproveedor' => $this->motivotraslado->code == '02' || $this->motivotraslado->code == '13' ? $this->rucproveedor : null,
                'nameproveedor' => $this->motivotraslado->code == '02' || $this->motivotraslado->code == '13' ? $this->nameproveedor : null,
                'direccionorigen' => $this->direccionorigen,
                'anexoorigen' => $this->anexoorigen ?? '0',
                'direcciondestino' => $this->direcciondestino,
                'anexodestino' => $this->anexodestino ?? '0',
                'note' => $this->note,
                'indicadorvehiculosml' => $this->vehiculosml ? 1 : 0,
                'indicadorvehretorvacio' => 0,
                'indicadorvehretorenvacios' => 0,
                'referencia' => empty($this->referencia) ? null : trim($this->referencia),
                'sendmode' => $this->empresa->sendmode,
                'motivotraslado_id' => $this->motivotraslado_id,
                'modalidadtransporte_id' => $this->modalidadtransporte_id,
                'ubigeoorigen_id' => $this->ubigeoorigen_id,
                'ubigeodestino_id' => $this->ubigeodestino_id,
                'client_id' => $client->id,
                'seriecomprobante_id' => $this->seriecomprobante->id,
                'sucursal_id' => $this->sucursal->id,
                'user_id' => auth()->user()->id,
                'guiable_id' => $this->comprobante_id,
                'guiable_type' => $this->comprobante_id ? Comprobante::class : null,
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

            foreach ($this->items as $item) {
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

                if (count($item->carshoopseries) > 0) {
                    foreach ($item->carshoopseries as $carshoopserie) {
                        $tvitem->itemseries()->create([
                            'date' => now('America/Lima'),
                            'serie_id' => $carshoopserie->serie_id,
                            'user_id' => auth()->user()->id
                        ]);
                    }
                }

                if ($item->kardex) {
                    $item->kardex->detalle = $item->isReservedStock() ? Kardex::RESERVADO_GUIA : Kardex::SALIDA_GUIA;
                    $item->kardex->reference = $this->seriecomprobante->serie . '-' . $contador;
                    $item->kardex->kardeable_id = $tvitem->id;
                    $item->kardex->kardeable_type = Tvitem::class;
                    $item->kardex->save();
                } else {
                    if ($item->isIncrementStock()) {
                        $producto = Producto::with('almacens')->find($item->producto_id);
                        $stock = $producto->almacens->find($item->almacen_id)->pivot->cantidad;

                        $tvitem->saveKardex(
                            $item->producto_id,
                            $item->almacen_id,
                            $stock,
                            $stock + $item->cantidad,
                            $item->cantidad,
                            Almacen::INGRESO_ALMACEN,
                            Kardex::ENTRADA_GUIA,
                            $this->seriecomprobante->serie . '-' . $contador
                        );

                        $producto->almacens()->updateExistingPivot($item->almacen_id, [
                            'cantidad' => $stock + $item->cantidad,
                        ]);
                    }
                }
            }

            $this->seriecomprobante->contador = $contador;
            $this->seriecomprobante->save();

            Carshoop::with(['carshoopseries'])->guias()->whereNull('moneda_id')->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)->each(function ($carshoop) {
                    $carshoop->carshoopseries()->delete();
                    $carshoop->delete();
                });

            DB::commit();
            $this->resetValidation();
            $this->resetExcept('modalidadtransporte', 'motivotraslado', 'sucursal', 'empresa', 'seriecomprobante');
            $this->dispatchBrowserEvent('created');

            if (auth()->user()->hasPermissionTo('admin.facturacion.guias.create')) {
                return redirect()->route('admin.facturacion.guias.edit', $guia);
            } else {
                return redirect()->route('admin.facturacion.guias');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedSeriecomprobanteId()
    {
        $this->reset(['motivotraslado_id']);
    }

    public function searchreferencia()
    {
        $this->referencia = mb_strtoupper(trim($this->referencia), "UTF-8");
        $this->validate(['referencia' => [
            'required',
            'string',
            'min:6',
            'max:13'
        ]]);

        if ($this->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
        }

        $comprobante = Comprobante::with('facturable')->ventas()->where('seriecompleta', $this->referencia)
            ->where('sucursal_id', $this->sucursal->id)->withTrashed()->first();

        if ($comprobante) {
            if ($comprobante->trashed()) {
                $mensaje =  response()->json([
                    'title' => 'COMPROBANTE ELECTRÓNICO SE ENCUENTRA ANULADO !',
                    'text' => "Comprobante electrónico se encuentra anulado, ingrese un nuevo comprobante."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($comprobante->guia) {
                $mensaje =  response()->json([
                    'title' => 'COMPROBANTE YA SE ENCUENTRA RELACIONADO A UNA GRE !',
                    'text' => "Comprobante electrónico se encuentra vinculado a la guía de remisión " . $comprobante->guia->seriecompleta . "."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }


            $tvitems = $comprobante->facturable->tvitems()->with('producto.unit', 'almacen', 'itemseries.serie')->get();
            if (count($tvitems) > 0) {
                $this->sincronizecpe = true;
                $this->comprobante_id = $comprobante->id;
                if ($this->motivotraslado->code == '03') {
                    $this->documentcomprador = $comprobante->client->document;
                    $this->namecomprador = $comprobante->client->name;
                } else {
                    $this->documentdestinatario = $comprobante->client->document;
                    $this->namedestinatario = $comprobante->client->name;
                }

                $this->confirmaradditemsguia($tvitems);
                $this->dispatchBrowserEvent('toast', toastJSON('Detalle del comprobante agregado correctamente'));
            } else {
                $this->addError('referencia', 'No se encontraron items del comprobante.');
            }
        } else {
            $this->addError('referencia', 'No se encontraron resultados.');
        }
    }

    public function confirmaradditemsguia($tvitems)
    {
        foreach ($tvitems as $item) {
            $date = now('America/Lima');
            $carshoop = Carshoop::create([
                'date' => $date,
                'cantidad' =>  $item->cantidad,
                'pricebuy' => $item->producto->pricebuy,
                'price' => 0,
                'igv' => 0,
                'subtotal' => 0,
                'total' => 0,
                'gratuito' => 0,
                'status' => 0,
                'producto_id' => $item->producto_id,
                'almacen_id' => $item->almacen_id,
                'moneda_id' => null,
                'user_id' => auth()->user()->id,
                'sucursal_id' => $this->sucursal->id,
                'alterstock' => Almacen::NO_ALTERAR_STOCK,
                'cartable_type' => Guia::class,
            ]);

            if (count($item->itemseries) > 0) {
                foreach ($item->itemseries as $itemserie) {
                    if (Carshoopserie::where('serie_id', $itemserie->serie_id)->exists()) {
                        $mensaje =  response()->json([
                            'title' => 'SERIE YA SE ENCUENTRA AGREGADO EN LOS ITEMS !',
                            'text' => "La serie " . $itemserie->serie->serie . " ya se encuentra registrado en los items de la Guía."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    $carshoop->carshoopseries()->create([
                        'date' =>  $date,
                        'serie_id' => $itemserie->serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }
        }
    }

    public function desvincularcpe()
    {
        $this->reset(['referencia', 'sincronizecpe', 'comprobante_id']);
    }

    public function addtoguia()
    {
        $this->mode = $this->mode > 0 ? $this->mode : 0;
        $this->validate([
            'almacen_id' => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'cantidad' => [
                'nullable',
                Rule::requiredIf(count($this->series) == 0 || in_array($this->mode, [Almacen::NO_ALTERAR_STOCK, Almacen::INCREMENTAR_STOCK])),
                'integer',
                'min:1',
                in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK]) ? new ValidateStock($this->producto_id, $this->almacen_id) : ''
            ],
            'serie_id' => [
                'nullable',
                Rule::requiredIf(count($this->series) > 0 && in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])),
                'integer',
                'min:1',
                'exists:series,id'
            ],
            'mode' => [
                'nullable',
                'integer',
                'min:0',
                'max:3',
                Rule::in([Almacen::NO_ALTERAR_STOCK, Almacen::RESERVAR_STOCK, Almacen::INCREMENTAR_STOCK, Almacen::DISMINUIR_STOCK])
            ],
        ]);

        $producto = Producto::with('almacens')->find($this->producto_id);
        $stock = $producto->almacens->find($this->almacen_id)->pivot->cantidad;

        if (in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
            if ($stock && $stock > 0) {
                if (($stock - $this->cantidad) < 0) {
                    $this->addError('cantidad', 'Cantidad supera el stock');
                    return false;
                }
            }
        }

        DB::beginTransaction();
        try {

            $existskardex = false;
            $date = now('America/Lima');
            $existsInCart = Carshoop::where('producto_id', $this->producto_id)
                ->where('almacen_id', $this->almacen_id)
                ->whereNull('moneda_id')->where('gratuito', 0)
                ->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->where('alterstock', $this->mode)
                ->where('cartable_type', Guia::class);


            if ($existsInCart->exists()) {
                $cantidad = $this->serie_id ? 1 : $this->cantidad;
                $carshoop = $existsInCart->first();
                $carshoop->cantidad = $carshoop->cantidad + $cantidad;
                $carshoop->pricebuy = $producto->pricebuy;
                $carshoop->save();

                if ($carshoop->kardex) {
                    $existskardex = true;
                    $carshoop->kardex->cantidad = $carshoop->kardex->cantidad +  $cantidad;
                    $carshoop->kardex->newstock = $carshoop->kardex->newstock - $cantidad;
                    $carshoop->kardex->save();
                }
            } else {
                $carshoop = Carshoop::create([
                    'date' => $date,
                    'cantidad' => $this->serie_id ? 1 : $this->cantidad,
                    'pricebuy' => $producto->pricebuy,
                    'price' => 0,
                    'igv' => 0,
                    'subtotal' => 0,
                    'total' => 0,
                    'gratuito' => 0,
                    'status' => 0,
                    'producto_id' => $this->producto_id,
                    'almacen_id' => $this->almacen_id,
                    'moneda_id' => null,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => $this->sucursal->id,
                    'alterstock' => $this->mode,
                    'cartable_type' => Guia::class,
                ]);
            }

            if ($this->serie_id) {
                $serie = Serie::find($this->serie_id);

                if (Carshoopserie::where('serie_id', $this->serie_id)->exists()) {
                    $mensaje =  response()->json([
                        'title' => 'SERIE YA SE ENCUENTRA AGREGADO EN LOS ITEMS !',
                        'text' => "La serie $serie->serie ya se encuentra registrado en los items de la Guía."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if (in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
                    if ($serie->isDisponible()) {
                        $serie->status = ($this->mode == Almacen::RESERVAR_STOCK) ? 1 : 2;
                        $serie->dateout = $date;
                        $serie->save();
                    } else {
                        $mensaje =  response()->json([
                            'title' => 'SERIE NO SE ENCUENTRA DISPONIBLE !',
                            'text' => "La serie $serie->serie ya no se encuentra disponible en este momento."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                }

                $carshoop->carshoopseries()->create([
                    'date' =>  $date,
                    'serie_id' => $this->serie_id,
                    'user_id' => auth()->user()->id
                ]);
            }

            if (in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
                if (!$existskardex) {
                    $cantidad = $this->serie_id ? 1 : $this->cantidad;
                    $producto = Producto::with('almacens')->find($this->producto_id);
                    $stock = $producto->almacens->find($this->almacen_id)->pivot->cantidad;

                    $carshoop->saveKardex(
                        $this->producto_id,
                        $this->almacen_id,
                        $stock,
                        $stock - $cantidad,
                        $cantidad,
                        Almacen::SALIDA_ALMACEN,
                        Kardex::ADD_GUIA,
                        null
                    );
                }

                $producto->almacens()->updateExistingPivot($this->almacen_id, [
                    'cantidad' => $stock - $cantidad,
                ]);
            }

            DB::commit();
            $this->dispatchBrowserEvent('created');
            if ($this->clearaftersave) {
                $this->reset(['serie_id', 'producto_id', 'almacen_id', 'alterstock', 'mode', 'cantidad']);
            } else {
                $this->reset(['serie_id', 'cantidad']);
                $this->series = Serie::disponibles()->where('almacen_id', $this->almacen_id)
                    ->where('producto_id', $this->producto_id)
                    ->orderBy('serie', 'asc')->get();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Carshoop $carshoop)
    {
        DB::beginTransaction();
        try {
            if (count($carshoop->carshoopseries) > 0) {
                $carshoop->carshoopseries()->each(function ($carshoopserie) use ($carshoop) {
                    if ($carshoop->isDiscountStock() || $carshoop->isReservedStock()) {
                        $carshoopserie->serie->dateout = null;
                        $carshoopserie->serie->status = 0;
                        $carshoopserie->serie->save();
                    }
                    $carshoopserie->delete();
                });
            }

            if ($carshoop->isDiscountStock() || $carshoop->isReservedStock()) {
                $stock = $carshoop->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;
                $carshoop->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
                    'cantidad' => $stock + $carshoop->cantidad,
                ]);
            }

            if ($carshoop->kardex) {
                $carshoop->kardex->delete();
            }

            $carshoop->delete();
            if ($this->producto_id && $this->almacen_id) {
                $query = Serie::where('almacen_id', $this->almacen_id)
                    ->where('producto_id', $this->producto_id);

                if (in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
                    $query->disponibles();
                }
                $this->series = $query->orderBy('serie', 'asc')->get();
            }
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteserie(Carshoopserie $carshoopserie)
    {
        DB::beginTransaction();
        try {
            if ($carshoopserie->carshoop->isDiscountStock() || $carshoopserie->carshoop->isReservedStock()) {
                $carshoopserie->serie->dateout = null;
                $carshoopserie->serie->status = 0;
                $carshoopserie->serie->save();
                $carshoopserie->carshoop->cantidad = $carshoopserie->carshoop->cantidad - 1;
                $carshoopserie->carshoop->save();

                $stock = $carshoopserie->carshoop->producto->almacens->find($carshoopserie->carshoop->almacen_id)->pivot->cantidad;
                $carshoopserie->carshoop->producto->almacens()->updateExistingPivot($carshoopserie->carshoop->almacen_id, [
                    'cantidad' => $stock + 1,
                ]);
            }

            if ($carshoopserie->carshoop->kardex) {
                $carshoopserie->carshoop->kardex->cantidad = $carshoopserie->carshoop->kardex->cantidad - 1;
                $carshoopserie->carshoop->kardex->newstock = $carshoopserie->carshoop->kardex->newstock + 1;
                $carshoopserie->carshoop->kardex->save();
            }

            $carshoopserie->delete();

            if ($this->producto_id && $this->almacen_id) {
                $query = Serie::where('almacen_id', $this->almacen_id)
                    ->where('producto_id', $this->producto_id);

                if (in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
                    $query->disponibles();
                }
                $this->series = $query->orderBy('serie', 'asc')->get();
            }
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteallcarshoop()
    {
        try {
            DB::beginTransaction();
            Carshoop::with(['carshoopseries'])->guias()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)->each(function ($carshoop) {
                    $this->delete($carshoop);
                });
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('toast', toastJSON('Carrito de guías eliminado correctamente'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedDisponibles($value)
    {
        $this->reset(['productos', 'producto_id', 'series', 'serie_id']);
        if ($this->almacen_id) {

            $this->productos = Producto::query()->select(
                'productos.id',
                'productos.name',
                'marca_id',
                'category_id',
                'subcategory_id',
                'visivility',
                'novedad',
                'pricebuy',
                'pricesale',
                'precio_1',
                'precio_2',
                'precio_3',
                'precio_4',
                'precio_5',
                'marcas.name as name_marca',
                'categories.name as name_category',
                'subcategories.name as name_subcategory',
            )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
                ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
                ->addSelect(['image' => function ($query) {
                    $query->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('default', 'desc')->limit(1);
                }])->withWhereHas('almacens', function ($query) use ($value) {
                    $query->where('almacens.id', $this->almacen_id);
                    if ($value) {
                        $query->where('cantidad', '>', 0);
                    }
                })->visibles()->orderByDesc('novedad')
                ->orderBy('subcategories.orden', 'ASC')
                ->orderBy('categories.orden', 'ASC')->get();
        }
    }

    public function updatedMode($value)
    {
        $this->reset(['serie_id']);
        if ($this->producto_id && $this->almacen_id) {
            $query = Serie::where('almacen_id', $this->almacen_id)
                ->where('producto_id', $this->producto_id);

            if (in_array($value, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
                $query->disponibles();
            }

            $this->series = $query->orderBy('serie', 'asc')->get();
        }
    }

    public function updatedAlmacenId($value)
    {
        $this->reset(['productos', 'almacen_id', 'series', 'serie_id']);
        if ($value) {
            $this->almacen_id = $value;
            $this->productos = Producto::query()->select(
                'productos.id',
                'productos.name',
                'marca_id',
                'category_id',
                'subcategory_id',
                'visivility',
                'novedad',
                'pricebuy',
                'pricesale',
                'precio_1',
                'precio_2',
                'precio_3',
                'precio_4',
                'precio_5',
                'marcas.name as name_marca',
                'categories.name as name_category',
                'subcategories.name as name_subcategory',
            )->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
                ->leftJoin('subcategories', 'productos.subcategory_id', '=', 'subcategories.id')
                ->leftJoin('categories', 'productos.category_id', '=', 'categories.id')
                ->addSelect(['image' => function ($query) {
                    $query->select('url')->from('images')
                        ->whereColumn('images.imageable_id', 'productos.id')
                        ->where('images.imageable_type', Producto::class)
                        ->orderBy('default', 'desc')->limit(1);
                }])->withWhereHas('almacens', function ($query) use ($value) {
                    $query->where('almacens.id', $value);
                    if ($this->disponibles) {
                        $query->where('cantidad', '>', 0);
                    }
                })->visibles()->orderByDesc('novedad')
                ->orderBy('subcategories.orden', 'ASC')
                ->orderBy('categories.orden', 'ASC')->get();
        }
    }

    public function updatedProductoId($value)
    {
        $this->reset(['series', 'serie_id']);
        if ($value) {
            $this->producto_id = $value;
            $query = Serie::where('almacen_id', $this->almacen_id)
                ->where('producto_id', $this->producto_id);

            if (in_array($this->mode, [Almacen::DISMINUIR_STOCK, Almacen::RESERVAR_STOCK])) {
                $query->disponibles();
            }

            $this->series = $query->orderBy('serie', 'asc')->get();
        }
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
            $this->resetValidation();
            $this->reset(['placa']);
        }
    }

    public function deleteplacavehiculo($id)
    {
        unset($this->placavehiculos[$id]);
    }

    public function searchclient($property, $name)
    {
        $this->authorize('admin.clientes.create');
        $this->resetValidation();
        $this->$property = trim($this->$property);

        if ($property == 'rucproveedor') {
            $rules = [
                'rucproveedor' => [
                    'required',
                    'numeric',
                    'digits:11',
                    'regex:/^\d{11}$/',
                    $this->motivotraslado->code == '02' ? 'different:documentdestinatario' : '',
                    in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : ''
                ]
            ];
        } elseif ($property == 'ructransport') {
            $rules = [
                'ructransport' => [
                    'required',
                    'numeric',
                    'digits:11',
                    'regex:/^\d{11}$/',
                    $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : ''
                ]
            ];
        } elseif ($property == 'documentdestinatario') {
            $rules = [
                'documentdestinatario' => [
                    'required',
                    'numeric',
                    $this->motivotraslado->code == '06' ? 'regex:/^\d{11}$/' : 'regex:/^\d{8}(?:\d{3})?$/',
                    $this->motivotraslado->code == '03' ? 'different:documentcomprador' : '',
                    in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : '')
                ]
            ];
        } elseif ($property == 'documentcomprador') {
            $rules = [
                'documentcomprador' => [
                    'required',
                    'numeric',
                    'regex:/^\d{8}(?:\d{3})?$/',
                    $this->motivotraslado->code == '03' || $this->motivotraslado->code == '13' ? 'different:documentdestinatario' : '',
                ]
            ];
        } elseif ($property == 'documentdriver') {
            $rules = [
                'documentdriver' => [Rule::requiredIf($this->modalidadtransporte->code == '02'), 'numeric', 'regex:/^\d{8}(?:\d{3})?$/']
            ];
        }

        $this->validate($rules);
        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->$property,
            'searchbd' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->$name = $cliente->name;
                if ($property == 'document') {
                }
            } else {
                $this->$name = '';
                if ($property == 'document') {
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
}
