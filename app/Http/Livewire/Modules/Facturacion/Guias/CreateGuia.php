<?php

namespace App\Http\Livewire\Modules\Facturacion\Guias;

use App\Models\Almacen;
use App\Models\Client;
use App\Models\Guia;
use App\Models\Itemserie;
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
    // public $productos = [];

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
    public $producto;
    public $almacens = [];

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

        $productos = Producto::query()->select(
            'productos.id',
            'productos.name',
            'marca_id',
            'category_id',
            'subcategory_id',
            'requireserie',
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
            ->with(['almacens', 'imagen'])->visibles()->orderByDesc('novedad')
            ->orderBy('subcategories.orden', 'ASC')
            ->orderBy('categories.orden', 'ASC')->get();

        $tvitems = Tvitem::with(['itemseries.serie', 'kardexes.almacen', 'producto' => function ($query) {
            $query->with(['imagen', 'unit', 'marca']);
        }])->guias()->micart()->orderBy('id', 'asc')->get();

        return view('livewire.modules.facturacion.guias.create-guia', compact('modalidadtransportes', 'motivotraslados', 'seriecomprobantes', 'almacens', 'ubigeos', 'productos', 'tvitems'));
    }

    public function save()
    {

        $this->authorize('admin.facturacion.guias.create');
        $this->referencia = trim($this->referencia);
        $this->rucproveedor = trim($this->rucproveedor);
        $this->nameproveedor = trim($this->nameproveedor);
        $this->seriecomprobante_id = empty($this->seriecomprobante_id) ? null : $this->seriecomprobante_id;
        $this->items = Tvitem::with(['itemseries.serie', 'kardexes.almacen', 'producto'])
            ->guias()->micart()->orderBy('id', 'asc')->get();

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

            foreach ($this->items as $tvitem) {
                $tvitem->tvitemable_id = $guia->id;
                $tvitem->tvitemable_type = Guia::class;
                $tvitem->save();
            }

            $this->seriecomprobante->contador = $contador;
            $this->seriecomprobante->save();
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
        $this->validate([
            'referencia' => ['required', 'string', 'min:6', 'max:13']
        ]);

        if ($this->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
        }

        $comprobante = Comprobante::with(['facturable' => function ($query) {
            $query->with(['tvitems' => function ($q) {
                $q->with(['itemseries.serie', 'kardexes', 'producto']);
            }]);
        }])->ventas()->where('seriecompleta', $this->referencia)
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

            if (count($comprobante->facturable->tvitems) > 0) {
                $this->sincronizecpe = true;
                $this->comprobante_id = $comprobante->id;
                if ($this->motivotraslado->code == '03') {
                    $this->documentcomprador = $comprobante->client->document;
                    $this->namecomprador = $comprobante->client->name;
                } else {
                    $this->documentdestinatario = $comprobante->client->document;
                    $this->namedestinatario = $comprobante->client->name;
                }

                Self::confirmaradditemsguia($comprobante->facturable->tvitems);
            } else {
                $this->addError('referencia', 'No se encontraron items del comprobante.');
            }
        } else {
            $this->addError('referencia', 'No se encontraron resultados.');
        }
    }

    public function confirmaradditemsguia($tvitems)
    {
        DB::beginTransaction();
        try {
            foreach ($tvitems as $item) {
                $date = now('America/Lima');
                $tvitem = Tvitem::with(['kardexes', 'itemseries'])->guias()->micart()
                    ->where('alterstock', Almacen::NO_ALTERAR_STOCK,)
                    ->where('producto_id', $item->producto_id)->first();

                if (empty($tvitem)) {
                    $tvitem = Tvitem::create([
                        'date' => $date,
                        'cantidad' =>  $item->cantidad,
                        'pricebuy' => $item->producto->pricebuy,
                        'price' => 0,
                        'igv' => 0,
                        'subtotal' => 0,
                        'subtotaligv' => 0,
                        'total' => 0,
                        'gratuito' => 0,
                        'status' => 0,
                        'producto_id' => $item->producto_id,
                        'user_id' => auth()->user()->id,
                        'sucursal_id' => auth()->user()->sucursal_id,
                        'alterstock' => Almacen::NO_ALTERAR_STOCK,
                        'tvitemable_type' => Guia::class,
                    ]);
                } else {
                    $tvitem->cantidad = $tvitem->cantidad + $item->cantidad;
                    $tvitem->pricebuy = $item->producto->pricebuy;
                    $tvitem->save();
                }

                if (count($item->itemseries) > 0) {
                    foreach ($item->itemseries as $itemserie) {
                        if ($tvitem->itemseries()->where('serie_id', $itemserie->serie_id)->exists()) {
                            $mensaje =  response()->json([
                                'title' => "SERIE " . $itemserie->serie->serie . " YA SE ENCUENTRA AGREGADO !",
                                'text' => null
                            ])->getData();
                            $this->dispatchBrowserEvent('validation', $mensaje);
                            return false;
                        }

                        $tvitem->itemseries()->create([
                            'date' =>  $date,
                            'serie_id' => $itemserie->serie_id,
                            'user_id' => auth()->user()->id
                        ]);
                    }
                }

                if (count($item->kardexes) > 0) {
                    foreach ($item->kardexes as $itemkardex) {
                        $kardex = $tvitem->kardexes()->where('almacen_id', $itemkardex->almacen_id)->first();

                        if (empty($kardex)) {
                            $kardex = $tvitem->saveKardex(
                                $itemkardex->almacen_id,
                                $itemkardex->oldstock,
                                $itemkardex->newstock, //$stock - $cantidad,
                                $itemkardex->cantidad,
                                Almacen::SALIDA_ALMACEN,
                                Kardex::ADD_GUIA,
                            );
                        } else {
                            $stock = $tvitem->producto->almacens()->find($itemkardex->almacen_id)->pivot->cantidad;
                            $kardex->cantidad = $kardex->cantidad +  $itemkardex->cantidad;
                            $kardex->newstock = $stock - $itemkardex->cantidad;
                            $kardex->save();
                        }
                    }
                }
            }
            DB::commit();
            $this->dispatchBrowserEvent('toast', toastJSON('PRODUCTOS AGREGADOS CORRECTAMENTE'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function desvincularcpe()
    {
        $this->reset(['referencia', 'sincronizecpe', 'comprobante_id']);
    }

    public function addtoguia($key)
    {
        $this->mode = $this->mode > 0 ? $this->mode : 0;
        $validateData = $this->validate([
            "almacens.$key.id" => ['required', 'integer', 'min:1', 'exists:almacens,id'],
            'producto_id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            "almacens.$key.cantidad" => $this->producto->isRequiredserie() ?
                ['nullable'] : [
                    'required',
                    'integer',
                    'gt:0',
                    !in_array($this->mode, [Almacen::NO_ALTERAR_STOCK, Almacen::INCREMENTAR_STOCK]) ? new ValidateStock($this->producto_id, $this->almacens[$key]['id'], $this->almacens[$key]['cantidad']) : ''
                ],
            "almacens.$key.serie_id" => $this->producto->isRequiredserie() ?
                [
                    Rule::requiredIf($this->producto->isRequiredserie()),
                    'integer',
                    'min:1',
                    'exists:series,id',
                    $this->mode !== Almacen::NO_ALTERAR_STOCK ? new ValidateStock($this->producto_id, $this->almacens[$key]['id'], 1) : '',
                ] : ['nullable'],
            'mode' => [
                'nullable',
                'integer',
                'min:0',
                'max:3',
                Rule::in([Almacen::NO_ALTERAR_STOCK, Almacen::RESERVAR_STOCK, Almacen::INCREMENTAR_STOCK, Almacen::DISMINUIR_STOCK])
            ],
        ], [], [
            "almacens.$key.id" => 'almacen',
            "almacens.$key.cantidad" => 'cantidad',
            "almacens.$key.serie_id" => 'serie',
        ]);

        DB::beginTransaction();
        try {
            $date = now('America/Lima');
            $serie_id = $this->producto->isRequiredserie() && !empty($this->almacens[$key]['serie_id']) ? $this->almacens[$key]['serie_id'] : null;
            $cantidad = $this->producto->isRequiredserie() ? 1 : $this->almacens[$key]['cantidad'];
            $stock = $this->producto->almacens()->find($key)->pivot->cantidad;

            $tvitem = Tvitem::with(['producto', 'kardexes', 'itemseries', 'promocion'])->guias()->micart()
                ->inCart($this->producto_id, Tvitem::NO_GRATUITO, $this->mode)
                ->first();

            if (empty($tvitem)) {
                $tvitem = Tvitem::create([
                    'date' => $date,
                    'cantidad' => $cantidad,
                    'pricebuy' => $this->producto->pricebuy,
                    'price' => 0,
                    'igv' => 0,
                    'subtotal' => 0,
                    'subtotaligv' => 0,
                    // 'subtotal' => 0,
                    'total' => 0,
                    'gratuito' => 0,
                    'status' => 0,
                    'alterstock' => $this->mode,
                    'producto_id' => $this->producto_id,
                    'user_id' => auth()->user()->id,
                    'sucursal_id' => auth()->user()->sucursal_id,
                    'tvitemable_type' => Guia::class,
                ]);
            } else {
                $tvitem->cantidad = $tvitem->cantidad + $cantidad;
                $tvitem->pricebuy = $this->producto->pricebuy;
                $tvitem->save();
            }

            if (!empty($serie_id)) {
                $serie = Serie::find($serie_id);

                if ($tvitem->itemseries()->where('serie_id', $serie_id)->exists()) {
                    $mensaje =  response()->json([
                        'title' => "SERIE $serie->serie YA SE ENCUENTRA AGREGADO !",
                        'text' => null
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                    if (!$serie->isDisponible()) {
                        $mensaje =  response()->json([
                            'title' => "SERIE $serie->serie NO SE ENCUENTRA DISPONIBLE !",
                            'text' => null
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }

                    $tvitem->registrarSalidaSerie($serie_id,  $tvitem->isReservedStock() ? Serie::RESERVADA : Serie::SALIDA);
                } else {
                    $tvitem->itemseries()->create([
                        'date' =>  $date,
                        'serie_id' => $serie_id,
                        'user_id' => auth()->user()->id
                    ]);
                }
            }

            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $kardex = $tvitem->updateOrCreateKardex($key, $stock, $cantidad);
                $tvitem->producto->descontarStockProducto($key, $cantidad);
            }

            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->producto->refresh();
            foreach ($this->producto->almacens as $item) {
                $this->almacens[$item->id]['id'] = $item->id;
                $this->almacens[$item->id]['serie_id'] = null;
                $this->almacens[$item->id]['cantidad'] = 0;
                $this->almacens[$item->id]['producto_id'] = $this->producto_id;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(Tvitem $tvitem)
    {
        DB::beginTransaction();
        try {
            $tvitem->load(['itemseries.serie', 'producto.almacens', 'kardexes']);

            foreach ($tvitem->itemseries as $itemserie) {
                if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                    $tvitem->updateSerieDisponible($itemserie->serie);
                }
                $itemserie->delete();
            }

            foreach ($tvitem->kardexes as $kardex) {
                $kardex->incrementOrDecrementStock($tvitem->producto, $tvitem);
                $kardex->delete();
            }

            $tvitem->forceDelete();
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

    public function deletekardex(Tvitem $tvitem, Kardex $kardex)
    {
        DB::beginTransaction();
        try {
            $tvitem->load(['producto.almacens']);
            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->producto->incrementarStockProducto($kardex->almacen_id, $kardex->cantidad);

                // COmparar si tiene seleccionado y es el mismo
                if (!empty($this->producto_id) && $tvitem->producto_id == $this->producto_id) {
                    $this->producto->refresh();
                    foreach ($this->producto->almacens as $item) {
                        $this->almacens[$item->id]['id'] = $item->id;
                        $this->almacens[$item->id]['serie_id'] = null;
                        $this->almacens[$item->id]['cantidad'] = 0;
                        $this->almacens[$item->id]['producto_id'] = $this->producto->id;
                    }
                }
            }
            $kardex->delete();
            $tvitem->cantidad = $tvitem->cantidad - $kardex->cantidad;
            if ($tvitem->cantidad == 0) {
                $tvitem->forceDelete();
            } else {
                $tvitem->save();
            }
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteitemserie(Itemserie $itemserie)
    {
        DB::beginTransaction();
        try {
            $itemserie->load(['seriable.kardexes', 'serie'  => function ($query) {
                $query->with(['producto.almacens']);
            }]);
            $tvitem = $itemserie->seriable;
            $almacen_id = $itemserie->serie->almacen_id;
            $kardex = $tvitem->kardexes->where('almacen_id', $almacen_id)->first();

            if ($tvitem->isDiscountStock() || $tvitem->isReservedStock()) {
                $tvitem->updateSerieDisponible($itemserie->serie);
                if ($kardex) {
                    $itemserie->serie->producto->incrementarStockProducto($almacen_id, 1);
                }
            }

            if ($kardex) {
                $kardex->cantidad = $kardex->cantidad - 1;
                $kardex->newstock = $kardex->newstock - 1;
                if ($kardex->cantidad == 0) {
                    $kardex->delete();
                } else {
                    $kardex->save();
                }
            }
            $itemserie->delete();
            $tvitem->cantidad = $tvitem->cantidad - 1;
            if ($tvitem->cantidad == 0) {
                $tvitem->forceDelete();
            } else {
                $tvitem->save();
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

    public function deleteallitems()
    {
        try {
            DB::beginTransaction();
            Tvitem::with(['itemseries.serie', 'kardexes.almacen', 'producto' => function ($query) {
                $query->with(['imagen', 'unit', 'marca']);
            }])->guias()->micart()->orderBy('id', 'asc')->get()->map(function ($tvitem) {
                Self::delete($tvitem);
            });
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('toast', toastJSON('CARRITO ELIMINADO CORRECTAMENTE'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedProductoId($value)
    {
        $this->reset(['series', 'serie_id', 'almacens', 'producto']);
        if ($value) {
            $this->producto = Producto::with(['almacens', 'unit', 'series'])->find($value);
            foreach ($this->producto->almacens as $item) {
                $this->almacens[$item->id]['id'] = $item->id;
                $this->almacens[$item->id]['serie_id'] = null;
                $this->almacens[$item->id]['cantidad'] = 0;
                $this->almacens[$item->id]['producto_id'] = $value;
            }
        }
    }

    public function clearproducto()
    {
        $this->reset(['producto', 'producto_id', 'almacens', 'cantidad', 'mode']);
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
