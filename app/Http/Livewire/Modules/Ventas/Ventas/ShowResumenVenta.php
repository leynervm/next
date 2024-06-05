<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Enums\MovimientosEnum;
use App\Helpers\GetClient;
use App\Helpers\GetPrice;
use App\Models\Almacen;
use App\Models\Carshoop;
use App\Models\Carshoopserie;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Guia;
use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Modalidadtransporte;
use App\Models\Moneda;
use App\Models\Monthbox;
use App\Models\Motivotraslado;
use App\Models\Openbox;
use App\Models\Seriecomprobante;
use App\Models\Tvitem;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use App\Rules\ValidateCarrito;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Luecano\NumeroALetras\NumeroALetras;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowResumenVenta extends Component
{


    public $empresa, $sucursal, $typepayment, $moneda, $modalidadtransporte, $motivotraslado;
    public $cotizacion_id, $client_id, $direccion_id, $pricetype_id, $mensaje;
    public $moneda_id, $monthbox, $openbox, $methodpayment_id, $typepayment_id, $detallepago, $concept;
    public $typecomprobante, $typecomprobante_id, $seriecomprobante_id, $tribute_id;
    public $document, $name, $direccion, $pricetypeasigned;

    public $serieguia_id, $ructransport, $nametransport,
        $documentdriver, $namedriver, $lastname, $licencia, $placa, $placavehiculo,
        $documentdestinatario, $namedestinatario, $peso, $packages, $datetraslado,
        $note, $ubigeoorigen_id, $direccionorigen, $anexoorigen, $ubigeodestino_id,
        $direcciondestino, $anexodestino, $motivotraslado_id, $modalidadtransporte_id;

    public $vehiculosml = false;
    public $incluyeguia = false;
    public $incluyeigv = 0;
    public $increment = 0;
    public $countcuotas = 1;
    public $exonerado = 0;
    public $gravado = 0;
    public $gratuito = 0;
    public $inafecto = 0;
    public $igv = 0;
    public $descuentos = 0;
    public $otros = 0;
    public $subtotal = 0;
    public $total = 0;
    public $amountincrement = 0;
    public $paymentactual = 0;

    public $cuotas = [];
    public $items = [];

    public $arrayequalremite = ['02', '04', '07'];
    public $arraydistintremite = ['01', '03', '05', '06', '09', '14', '17'];

    public $searchgre, $guiaremision;
    public $sincronizegre = false;


    protected function rules()
    {
        return [
            // 'empresa_id' => ['required', 'integer', 'exists:empresas,id'],
            'document' => [
                'required', 'numeric',
                $this->typecomprobante->code == "01" ? 'digits:11' : new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ],
            'name' => ['required', 'string', 'min:6'],
            'direccion' => ['required', 'string', 'min:3'],
            'client_id' => ['required', 'integer', 'min:1', 'exists:clients,id'],
            'typecomprobante.id' => ['required', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'typepayment.id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'paymentactual' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 1),
                'numeric', 'min:0', $this->typepayment->paycuotas ? 'lt:' . $this->total : '',
                'decimal:0,2'
            ],
            'increment' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 1),
                'numeric', 'min:0', 'decimal:0,2'
            ],
            'countcuotas' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 1),
                'integer', 'min:1'
            ],
            'concept.id' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:concepts,id',
            ],
            'openbox.id' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:openboxes,id',
            ],
            'monthbox.id' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:monthboxes,id',
            ],
            'methodpayment_id' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:methodpayments,id',
            ],
            'detallepago' => ['nullable'],
            'cotizacion_id' => ['nullable', 'integer', 'min:1', 'exists:cotizacions,id'],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'seriecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'items' => ['required', 'array', 'min:1', new ValidateCarrito($this->moneda->id, $this->sucursal->id)],
            'ructransport' => [
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
            ],
            'nametransport' => [
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'string', 'min:6',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.name' : '',
            ],
            'documentdestinatario' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
                // 'different:document'
            ],
            'namedestinatario' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'min:6',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
            ],
            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                // 'different:document'
            ],
            'namedriver' => [
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:6'
            ],
            'lastname' => [
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:6'
            ],
            'licencia' => [
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:9', 'max:10'
            ],
            'placa' => [
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:6', 'max:8'
            ],
            'peso' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'numeric', 'gt:0', 'decimal:0,4',
            ],
            'packages' => [
                'nullable', Rule::requiredIf($this->incluyeguia), 'integer', 'min:1',
            ],
            'datetraslado' => [
                'nullable', Rule::requiredIf($this->incluyeguia), 'date', 'after_or_equal:today',
            ],
            'placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],
            'note' => ['nullable', 'string', 'min:10'],
            'ubigeoorigen_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:ubigeos,id'
            ],
            'direccionorigen' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'min:6',
            ],
            'ubigeodestino_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:ubigeos,id'
            ],
            'direcciondestino' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'min:6',
            ],
            'motivotraslado_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:motivotraslados,id'
            ],
            'modalidadtransporte_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:modalidadtransportes,id'
            ],
            'serieguia_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:seriecomprobantes,id'
            ],
        ];
    }

    public function mount(Seriecomprobante $seriecomprobante, Moneda $moneda, Concept $concept)
    {
        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->typepayment = new Typepayment();
        $this->methodpayment_id = Methodpayment::default()->first()->id ?? null;
        $this->sucursal = auth()->user()->sucursal;
        $this->empresa = auth()->user()->sucursal->empresa;
        $this->monthbox = Monthbox::usando($this->sucursal->id)->first();
        $this->openbox = Openbox::mybox($this->sucursal->id)->first();
        $this->typepayment_id = Typepayment::default()->first()->id ?? null;
        $this->seriecomprobante_id = $seriecomprobante->id ?? null;
        $this->typecomprobante = $seriecomprobante->typecomprobante;
        $this->moneda_id = $moneda->id ?? null;
        $this->moneda = $moneda;
        $this->concept = $concept;
        $this->setTotal();
        $this->ubigeoorigen_id = $this->sucursal->ubigeo_id;
        $this->direccionorigen = $this->sucursal->direccion;
    }

    public function render()
    {

        $ubigeos = Ubigeo::orderBy('region', 'asc')->orderBy('provincia', 'asc')
            ->orderBy('distrito', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        if (Module::isEnabled('Facturacion')) {
            $modalidadtransportes = Modalidadtransporte::orderBy('code', 'asc')->get();
            $motivotraslados = Motivotraslado::whereIn('code', ['01', '03'])->orderBy('code', 'asc')->get();
        } else {
            $modalidadtransportes = [];
            $motivotraslados = [];
        }

        $typecomprobantes = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                $query->whereNotIn('code', ['07', '09']);
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            });
        $comprobantesguia = auth()->user()->sucursal->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) {
                $query->whereIn('code', ['09', '13']);
                if (Module::isDisabled('Facturacion')) {
                    $query->default();
                }
            });

        $typecomprobantes = $typecomprobantes->orderBy('code', 'asc')->get();
        $comprobantesguia = $comprobantesguia->orderBy('code', 'asc')->get();
        $carshoops = Carshoop::with(['carshoopseries', 'producto', 'almacen', 'promocion', 'promocion.itempromos'])
            ->ventas()->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)->orderBy('id', 'asc')->paginate();

        if ($this->empresa->usepricedolar) {
            $monedas = Moneda::orderBy('currency', 'asc')->get();
        } else {
            $monedas = Moneda::where('code', 'PEN')->orderBy('currency', 'asc')->get();
        }

        return view('livewire.modules.ventas.ventas.show-resumen-venta', compact('carshoops', 'typecomprobantes', 'comprobantesguia', 'typepayments', 'methodpayments', 'monedas', 'modalidadtransportes', 'motivotraslados', 'ubigeos'));
    }

    public function limpiarcliente()
    {
        $this->reset(['client_id', 'document', 'name', 'direccion']);
    }

    public function setTypepayment($value)
    {
        $this->typepayment_id = $value;
    }

    public function updatedIncluyeigv($value)
    {
        $this->reset(['gravado', 'exonerado', 'igv']);
        $this->incluyeigv = $value == 1 ? 1 : 0;
        $this->setTotal();
    }

    public function setTotal()
    {

        // $resumen = json_decode(getTotalCarrito('carrito'));
        // $total = $resumen->total;
        // $this->gratuito = $resumen->gratuito;

        $sumatorias = Carshoop::ventas()->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)
            ->selectRaw('gratuito, COALESCE(SUM(total), 0) as total')->groupBy('gratuito')
            ->orderBy('total', 'desc')->get();

        $gratuito = 0;
        $total = 0;

        if (count($sumatorias) > 0) {
            foreach ($sumatorias as $item) {
                if ($item->gratuito == '0') {
                    $total = $item->total;
                }
                if ($item->gratuito == '1') {
                    $gratuito = $item->total;
                }
            }
        }
        $this->gratuito = $gratuito;
        $totalSinIncrement = number_format($total - $this->paymentactual ?? 0, 3, '.', '');
        $this->amountincrement = (($totalSinIncrement ?? 0) * $this->increment) / 100;
        $this->total = number_format($total + (($totalSinIncrement * $this->increment ?? 0) / 100), 3, '.', '');

        if ($this->incluyeigv) {
            $this->igv = number_format(($this->total * $this->empresa->igv) / (100 + $this->empresa->igv), 3, '.', '');
            $this->gravado = number_format($this->total - $this->igv, 3, '.', '');
        } else {
            $this->exonerado = number_format($this->total, 3, '.', '');
        }
    }

    public function updatedMonedaId($value)
    {
        if ($value) {
            $this->moneda = Moneda::find($value);
            // $this->dispatchBrowserEvent('setMoneda', $value);
        }
    }

    public function updatedTypepaymentId($value)
    {
        $this->reset([
            'increment', 'paymentactual', 'amountincrement',
            'gravado', 'exonerado', 'igv', 'countcuotas', 'cuotas'
        ]);
        if ($value) {
            $this->setTotal();
        }
    }

    public function updatedIncrement($value)
    {
        $this->setincrement($value);
    }

    public function updatedPaymentactual($value)
    {
        $this->setpaymentactual($value);
    }

    public function setpaymentactual($value)
    {
        $this->paymentactual = !empty($value) ? $value : 0;
        $this->setTotal();
        $this->resetValidation(['paymentactual']);
    }

    public function setincrement($value)
    {
        $this->increment = !empty($value) ? $value : 0;
        $this->setTotal();
    }

    public function save()
    {

        if (!$this->monthbox || !$this->monthbox->isUsing()) {
            $this->dispatchBrowserEvent('validation', getMessageMonthbox());
            return false;
        }

        if (!$this->openbox || !$this->openbox->isActivo()) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->direccion = trim($this->direccion);
        $this->ructransport = trim($this->ructransport);
        $this->nametransport = trim($this->nametransport);
        $this->direcciondestino = trim($this->direcciondestino);
        $this->direccionorigen = trim($this->direccionorigen);
        $this->note = trim($this->note);
        $this->documentdriver = trim($this->documentdriver);
        $this->name = trim($this->name);
        $this->lastname = trim($this->lastname);
        $this->licencia = trim($this->licencia);
        $this->placa = trim($this->placa);

        if ($this->typepayment_id) {
            $this->typepayment = Typepayment::find($this->typepayment_id);
        }

        if ($this->modalidadtransporte_id) {
            $this->modalidadtransporte = Modalidadtransporte::find($this->modalidadtransporte_id);
        }

        if ($this->motivotraslado_id) {
            $this->motivotraslado = Motivotraslado::find($this->motivotraslado_id);
        }

        if ($this->motivotraslado->code == '01') {
            $this->documentdestinatario = $this->document;
            $this->namedestinatario = $this->name;
        }

        if ($this->vehiculosml) {
            $this->reset(['documentdriver', 'namedriver', 'lastname', 'licencia', 'placa', 'ructransport', 'nametransport']);
        }

        $carshoops = Carshoop::with(['carshoopseries', 'producto', 'almacen'])
            ->ventas()->where('user_id', auth()->user()->id)
            ->where('sucursal_id', auth()->user()->sucursal_id)
            ->orderBy('id', 'asc')->get();
        $this->items = $carshoops;

        if ($this->seriecomprobante_id) {
            $seriecomprobante = Seriecomprobante::with('typecomprobante')->find($this->seriecomprobante_id);
            $this->typecomprobante = $seriecomprobante->typecomprobante;

            if ($this->typepayment->paycuotas && $this->typecomprobante->code <> '01') {
                $this->addError('typepayment_id', 'Tipo de pago no permitido para el comprobante seleccionado.');
                $mensaje =  response()->json([
                    'title' => 'TIPO DE PAGO NO PERMITIDO PARA EL COMPROBANTE SELECCIONADO !',
                    'text' => "Comprobante seleccionado no permite generar ventas con tipo de pago a CREDITO."
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            if ($this->guiaremision && $seriecomprobante->typecomprobante->isSunat()) {
                if (!$this->guiaremision->seriecomprobante->typecomprobante->isSunat()) {
                    $mensaje =  response()->json([
                        'title' => 'GRE LOCAL NO SE PUEDE VINCULAR A COMPROBANTE VENTA EMITIBLE A SUNAT !',
                        'text' => "No se puede vincular una Guía de remisión local a un comprobante de pago emitible a SUNAT."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }
            }

            $this->setTotal();
            $numeracion = $seriecomprobante->contador + 1;
        }

        $validatedData = $this->validate();
        DB::beginTransaction();

        try {

            $client = Client::find($this->client_id);
            $client->direccions()->updateOrCreate([
                'name' => $this->direccion,
            ]);

            $sumatorias = Carshoop::ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->selectRaw('gratuito, COALESCE(SUM(total), 0) as total')->groupBy('gratuito')
                ->orderBy('total', 'desc')->get();
            $itemsnogratuitos = Carshoop::ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)
                ->count() ?? 0;

            $gratuito = 0;
            $total = 0;
            $totalcomboGRA = 0;
            $totalIGVcomboGRA = 0;

            if (count($sumatorias) > 0) {
                foreach ($sumatorias as $item) {
                    if ($item->gratuito == '0') {
                        $total = $item->total;
                    }
                    if ($item->gratuito == '1') {
                        $gratuito = $item->total;
                    }
                }
            }

            //SOLO DEBE INCREMENTAR PORCENTAJE EN LOS ITEMS DEL CARRITO QUE NO SEAN GRATUITOS
            // CONTAMOS LOS ITEMS DEL CARITO QUE NO SEAN GRATUITOS

            if ($itemsnogratuitos > 0) {
                $totalSinIncrement = number_format($total - $this->paymentactual ?? 0, 3, '.', '');
            } else {
                $totalSinIncrement = number_format($total + $gratuito - $this->paymentactual ?? 0, 3, '.', '');
            }

            $totalAmountCuotas = number_format($totalSinIncrement + (($totalSinIncrement * $this->increment ?? 0) / 100), 3, '.', '');
            $amountCuota = number_format($totalAmountCuotas / $this->countcuotas, 3, '.', '');
            $gratuito = number_format($this->incluyeigv ? ($this->gratuito * 100) / (100 + $this->empresa->igv) : $this->gratuito, 3, '.', '');
            $igvgratuito = number_format($this->incluyeigv ? $this->gratuito - $gratuito : 0, 3, '.', '');

            $venta = Venta::create([
                'date' => now('America/Lima'),
                'seriecompleta' => $seriecomprobante->serie . '-' . $numeracion,
                'direccion' => $this->direccion,
                'exonerado' => number_format($this->exonerado, 34, '.', ''),
                'gravado' => number_format($this->gravado, 3, '.', ''),
                'gratuito' => number_format($gratuito, 3, '.', ''),
                'inafecto' => number_format($this->inafecto, 3, '.', ''),
                'descuento' => number_format($this->descuentos, 3, '.', ''),
                'otros' => number_format($this->otros, 3, '.', ''),
                'igv' => number_format($this->igv, 3, '.', ''),
                'igvgratuito' => number_format($igvgratuito, 3, '.', ''),
                'subtotal' => number_format($this->gravado + $this->exonerado + $this->inafecto, 3, '.', ''),
                'total' => number_format($this->total, 3, '.', ''),
                'tipocambio' => $this->moneda->code == 'USD' ? $this->empresa->tipocambio : null,
                'increment' => $this->increment ?? 0,
                'paymentactual' => number_format($this->typepayment->paycuotas ? $this->paymentactual : $this->total, 4, '.', ''),
                'moneda_id' => $this->moneda_id,
                'typepayment_id' => $this->typepayment_id,
                'client_id' => $client->id,
                'seriecomprobante_id' => $this->seriecomprobante_id,
                'sucursal_id' => $this->sucursal->id,
                'user_id' => auth()->user()->id,
            ]);

            // REVISAR CODIGO PARA VINCULAR UNA GRE CON VENTA U CPE

            if (!$this->typepayment->paycuotas || $this->paymentactual > 0) {
                $venta->savePayment(
                    $this->sucursal->id,
                    $this->paymentactual > 0 ? $this->paymentactual : $this->total,
                    $this->paymentactual > 0 ? $this->paymentactual : $this->total,
                    null,
                    $this->moneda_id,
                    $this->methodpayment_id,
                    MovimientosEnum::INGRESO->value,
                    $this->concept->id,
                    $this->openbox->id,
                    $this->monthbox->id,
                    $seriecomprobante->serie . '-' . $numeracion,
                    trim($this->detallepago),
                );
            }

            if (Module::isEnabled('Facturacion')) {
                if ($seriecomprobante->typecomprobante->isSunat()) {
                    $leyenda = new NumeroALetras();
                    $comprobante = $venta->comprobante()->create([
                        'seriecompleta' => $seriecomprobante->serie . '-' . $numeracion,
                        'date' => Carbon::now('America/Lima'),
                        'expire' => Carbon::now('America/Lima')->format('Y-m-d'),
                        'direccion' => $this->direccion,
                        'exonerado' => number_format($this->exonerado, 3, '.', ''),
                        'gravado' => number_format($this->gravado, 3, '.', ''),
                        'gratuito' => number_format($gratuito, 3, '.', ''),
                        'inafecto' => number_format($this->inafecto, 3, '.', ''),
                        'descuento' => number_format($this->descuentos, 3, '.', ''),
                        'otros' => number_format($this->otros, 3, '.', ''),
                        'igv' => number_format($this->igv, 3, '.', ''),
                        'igvgratuito' => number_format($igvgratuito, 3, '.', ''),
                        'subtotal' => number_format($this->gravado + $this->exonerado + $this->inafecto, 3, '.', ''),
                        'total' => number_format($this->total, 3, '.', ''),
                        'paymentactual' => number_format($this->typepayment->paycuotas ? $this->paymentactual : $this->total, 4, '.', ''),
                        'percent' => $this->empresa->igv,
                        'referencia' => $venta->seriecompleta,
                        'leyenda' => $leyenda->toInvoice($this->total, 2, 'NUEVOS SOLES'),
                        'client_id' => $client->id,
                        'typepayment_id' => $this->typepayment_id,
                        'seriecomprobante_id' => $this->seriecomprobante_id,
                        'moneda_id' => $this->moneda_id,
                        'sucursal_id' => $this->sucursal->id,
                        'user_id' => auth()->user()->id,
                    ]);

                    if ($this->incluyeguia) {
                        $serieguiaremision = Seriecomprobante::find($this->serieguia_id);
                        $numeracionguia = $serieguiaremision->contador + 1;

                        $guia = $comprobante->guia()->create([
                            'seriecompleta' => $serieguiaremision->serie . '-' . $numeracionguia,
                            'date' => Carbon::now('America/Lima'),
                            'expire' => Carbon::now('America/Lima')->format('Y-m-d'),
                            'datetraslado' => $this->datetraslado,
                            'ructransport' => $this->ructransport,
                            'nametransport' => $this->nametransport,
                            'rucproveedor' => null,
                            'nameproveedor' => null,
                            'placavehiculo' => $this->placavehiculo,
                            'documentdestinatario' => $this->documentdestinatario,
                            'namedestinatario' => $this->namedestinatario,
                            'documentcomprador' => $this->motivotraslado->code == '03' ? $this->document : null,
                            'namecomprador' => $this->motivotraslado->code == '03' ? $this->name : null,
                            'peso' => $this->peso,
                            'unit' => 'KGM',
                            'packages' => $this->packages,
                            'direccionorigen' => $this->direccionorigen,
                            'anexoorigen' => null,
                            'direcciondestino' => $this->direcciondestino,
                            'anexodestino' => null,
                            'note' => $this->note,
                            'indicadorvehiculosml' => $this->vehiculosml ? 1 : 0,
                            'indicadorvehretorvacio' => 0,
                            'indicadorvehretorenvacios' => 0,
                            'motivotraslado_id' => $this->motivotraslado_id,
                            'modalidadtransporte_id' => $this->modalidadtransporte_id,
                            'ubigeoorigen_id' => $this->ubigeoorigen_id,
                            'ubigeodestino_id' => $this->ubigeodestino_id,
                            'client_id' => $client->id,
                            'seriecomprobante_id' => $serieguiaremision->id,
                            'sucursal_id' => $this->sucursal->id,
                            'user_id' => auth()->user()->id,
                        ]);

                        if (!$this->vehiculosml) {
                            if ($this->modalidadtransporte->code == '02') {
                                $guia->transportdrivers()->create([
                                    'document' => $this->documentdriver,
                                    'name' => $this->name,
                                    'lastname' => $this->lastname,
                                    'licencia' => $this->licencia,
                                    'principal' => 1
                                ]);

                                $guia->transportvehiculos()->create([
                                    'placa' => $this->placa,
                                    'principal' => 1
                                ]);
                            }
                        }

                        $serieguiaremision->contador = $numeracionguia;
                        $serieguiaremision->save();
                    }
                }
            }

            if ($this->guiaremision) {
                if (isset($comprobante)) {
                    // dd($comprobante);
                    $this->guiaremision->guiable()->associate($comprobante);
                    $this->guiaremision->save();
                } else {
                    // dd($venta);
                    $this->guiaremision->guiable()->associate($venta);
                    $this->guiaremision->save();
                }
            }

            $counter = 1;
            foreach ($carshoops as $item) {

                $porcentajeIncr = 0;
                if ($item->gratuito == 0) {
                    $priceIncrItem = number_format(($totalAmountCuotas - $totalSinIncrement) / $itemsnogratuitos, 3, '.', '');
                    $porcentajeIncr = number_format($this->increment ?? 0, 3, '.', '');
                    $price = number_format($item->price + ($priceIncrItem / $item->cantidad), 3, '.', '');

                    if ($this->paymentactual > 0) {
                        $percentAmount = number_format((100 / $total) * $item->price, 3, '.', '');
                        $amountItem =  number_format(($totalAmountCuotas - $totalSinIncrement) * $percentAmount / 100, 3, '.', '');
                        $price = number_format($item->price + $amountItem, 3, '.', '');
                        $porcentajeIncr = number_format($amountItem * 100 / $price, 3, '.', '');
                    }
                } else {
                    $price = number_format($item->price, 3, '.', '');
                }

                // dd($carritoSum->countnogratuitos, $carritoSum->total, $totalSinIncrement, $priceIncrItem, $porcentajeIncr);
                $pricesale = $this->incluyeigv ? ($price * 100) / (100 + $this->empresa->igv) : $price;
                $igv = $this->incluyeigv ? $price - $pricesale : 0;
                $subtotalItemIGV = $this->incluyeigv ? $igv * $item->cantidad : 0;
                $subtotalItem = number_format($pricesale * $item->cantidad, 3, '.', '');
                $totalItem = number_format($pricesale * $item->cantidad + $subtotalItemIGV, 3, '.', '');

                $newTvitem = [
                    'date' => now('America/Lima'),
                    'cantidad' => $item->cantidad,
                    'pricebuy' => $item->pricebuy,
                    'price' => number_format($pricesale, 2, '.', ''),
                    'igv' => number_format($igv, 2, '.', ''),
                    'subtotaligv' => number_format($subtotalItemIGV, 2, '.', ''),
                    'subtotal' => number_format($subtotalItem, 2, '.', ''),
                    'total' => number_format($totalItem, 2, '.', ''),
                    'status' => 0,
                    'alterstock' => $item->mode,
                    'gratuito' => $item->gratuito,
                    'increment' => $porcentajeIncr,
                    'almacen_id' => $item->almacen_id,
                    'producto_id' => $item->producto_id,
                    'user_id' => auth()->user()->id
                ];

                // dd($newTvitem);

                $tvitem = $venta->tvitems()->create($newTvitem);
                if ($item->kardex) {
                    $item->kardex->detalle = Kardex::SALIDA_VENTA;
                    $item->kardex->reference = $seriecomprobante->serie . '-' . $numeracion;
                    $item->kardex->kardeable_id = $tvitem->id;
                    $item->kardex->kardeable_type = Tvitem::class;
                    $item->kardex->save();
                }

                if ($this->incluyeguia) {
                    $tvitemguia = $guia->tvitems()->create($newTvitem);
                }

                if (count($item->carshoopseries) > 0) {
                    foreach ($item->carshoopseries as $carshoopserie) {
                        $newTvserieitem = [
                            'date' => now('America/Lima'),
                            'serie_id' => $carshoopserie->serie_id,
                            'user_id' => auth()->user()->id,
                        ];

                        $tvitem->itemseries()->create($newTvserieitem);
                        if ($this->incluyeguia) {
                            $tvitemguia->itemseries()->create($newTvserieitem);
                        }
                    }
                }

                if ($item->gratuito) {
                    $afectacion = $this->incluyeigv ? '15' : '21';
                } else {
                    $afectacion = $this->incluyeigv ? '10' : '20';
                }

                $codeafectacion = $this->incluyeigv ? '1000' : '9997';
                $nameafectacion = $this->incluyeigv ? 'IGV' : 'EXO';
                $typeafectacion = $this->incluyeigv ? 'VAT' : 'VAT';
                $abreviatureafectacion = $this->incluyeigv ? 'S' : 'E';

                if (Module::isEnabled('Facturacion')) {
                    if ($seriecomprobante->typecomprobante->sendsunat) {
                        $comprobante->facturableitems()->create([
                            'item' => $counter,
                            'descripcion' => $item->producto->name,
                            'code' => $item->producto->code,
                            'cantidad' => $item->cantidad,
                            'price' => number_format($pricesale, 2, '.', ''),
                            'igv' => number_format($igv, 2, '.', ''),
                            'subtotaligv' => number_format($subtotalItemIGV, 2, '.', ''),
                            'subtotal' => number_format($subtotalItem, 2, '.', ''),
                            'total' => number_format($totalItem, 2, '.', ''),
                            'unit' => $item->producto->unit->code,
                            'codetypeprice' => $item->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas
                            'afectacion' => $afectacion,
                            'codeafectacion' => $item->gratuito ? '9996' : $codeafectacion,
                            'nameafectacion' => $item->gratuito ? 'GRA' : $nameafectacion,
                            'typeafectacion' => $item->gratuito ? 'FRE' : $typeafectacion,
                            'abreviatureafectacion' => $item->gratuito ? 'Z' : $abreviatureafectacion,
                            'percent' => $this->incluyeigv ? $this->empresa->igv : 0,
                        ]);
                    }
                }

                $counter++;

                if (count($item->carshoopitems) > 0) {
                    // $countercombo = $counter;
                    foreach ($item->carshoopitems as $carshoopitem) {
                        $stockCombo = $carshoopitem->producto->almacens->find($item->almacen_id)->pivot->cantidad;

                        $pricesaleCombo = $this->incluyeigv ? ($carshoopitem->price * 100) / (100 + $this->empresa->igv) : $carshoopitem->price;
                        $igvCombo = $this->incluyeigv ? $carshoopitem->price - $pricesaleCombo : 0;
                        $subtotalItemIGVCombo = $this->incluyeigv ? $igvCombo * $item->cantidad : 0;
                        $subtotalItemCombo = number_format($pricesaleCombo * $item->cantidad, 3, '.', '');
                        $totalItemCombo = number_format($subtotalItemCombo + $subtotalItemIGVCombo, 3, '.', '');
                        $totalIGVcomboGRA = $totalIGVcomboGRA + $subtotalItemIGVCombo;
                        $totalcomboGRA = $totalcomboGRA + $subtotalItemCombo;

                        $itemcombo = [
                            'date' => now('America/Lima'),
                            'cantidad' => $item->cantidad,
                            'pricebuy' => $carshoopitem->pricebuy,
                            'price' => number_format($pricesaleCombo, 3, '.', ''),
                            'igv' => number_format($igvCombo, 3, '.', ''),
                            'subtotaligv' => number_format($subtotalItemIGVCombo, 3, '.', ''),
                            'subtotal' => number_format($subtotalItemCombo, 3, '.', ''),
                            'total' => number_format($totalItemCombo, 3, '.', ''),
                            'status' => 0,
                            'alterstock' => Almacen::DISMINUIR_STOCK,
                            'gratuito' => Tvitem::GRATUITO,
                            'increment' => 0,
                            'requireserie' => $carshoopitem->requireserie,
                            'almacen_id' => $item->almacen_id,
                            'producto_id' => $carshoopitem->producto_id,
                            'user_id' => auth()->user()->id
                        ];

                        $tvitem = $venta->tvitems()->create($itemcombo);
                        if ($this->incluyeguia) {
                            $tvitemguia = $guia->tvitems()->create($itemcombo);
                        }

                        if (Module::isEnabled('Facturacion')) {
                            if ($seriecomprobante->typecomprobante->sendsunat) {
                                $comprobante->facturableitems()->create([
                                    'item' => $counter,
                                    'descripcion' => $carshoopitem->producto->name,
                                    'code' => $carshoopitem->producto->code,
                                    'cantidad' => $item->cantidad,
                                    'price' => number_format($pricesaleCombo, 2, '.', ''),
                                    'igv' => number_format($igvCombo, 2, '.', ''),
                                    'subtotaligv' => number_format($subtotalItemIGVCombo, 2, '.', ''),
                                    'subtotal' => number_format($subtotalItemCombo, 2, '.', ''),
                                    'total' => number_format($totalItemCombo, 2, '.', ''),
                                    'unit' => $item->producto->unit->code,
                                    'codetypeprice' => '02',
                                    'afectacion' => $this->incluyeigv ? '15' : '21',
                                    'codeafectacion' => '9996',
                                    'nameafectacion' => 'GRA',
                                    'typeafectacion' => 'FRE',
                                    'abreviatureafectacion' => 'Z',
                                    'percent' => $this->incluyeigv ? $this->empresa->igv : 0,
                                ]);
                            }
                        }

                        // GUARDAMOS EN KARDEX YA QUE CUANDO AGREGAMOS AL CARRITO
                        // SOLAMENTE DESCUENTA STOCK MAS NO REGISTRA EN KARDEX
                        // LE AUMENTO EL STOCK EN KARDEX PORQUE SE SUPONE QUE HUBO 
                        // EN ALMACEN EL STOCK ACTUAL MAS LO DESCONTADO Y ESTOCK ES EL ACTUAL
                        $tvitem->saveKardex(
                            $carshoopitem->producto_id,
                            $item->almacen_id,
                            $stockCombo + $item->cantidad,
                            $stockCombo,
                            $item->cantidad,
                            Almacen::SALIDA_ALMACEN,
                            Kardex::SALIDA_COMBO_VENTA,
                            $seriecomprobante->serie . '-' . $numeracion,
                            $item->promocion_id,
                        );

                        $carshoopitem->delete();
                        $counter++;
                    }

                    if ($totalcomboGRA > 0 || $totalIGVcomboGRA > 0) {
                        $venta->gratuito = number_format($venta->gratuito + $totalcomboGRA, 4, '.', '');
                        $venta->igvgratuito = number_format($venta->igvgratuito + $totalIGVcomboGRA, 4, '.', '');
                        $venta->save();

                        if (Module::isEnabled('Facturacion')) {
                            if ($seriecomprobante->typecomprobante->sendsunat) {
                                $comprobante->gratuito = number_format($comprobante->gratuito + $totalcomboGRA, 4, '.', '');
                                $comprobante->igvgratuito = number_format($comprobante->igvgratuito + $totalIGVcomboGRA, 4, '.', '');
                                $comprobante->save();
                            }
                        }
                    }
                }
            }

            if ($this->typepayment->paycuotas) {
                if ((!empty(trim($this->countcuotas))) || $this->countcuotas > 0) {

                    $date = Carbon::now('America/Lima')->addMonth()->format('Y-m-d');
                    $sumaCuotas = 0.00;

                    for ($i = 1; $i <= $this->countcuotas; $i++) {
                        $sumaCuotas = number_format($sumaCuotas + $amountCuota, 2, '.', '');
                        if ($i == $this->countcuotas) {
                            $result = number_format($totalAmountCuotas - $sumaCuotas, 2, '.', '');
                            $amountCuota = number_format($amountCuota + ($result), 2, '.', '');
                        }

                        $venta->cuotas()->create([
                            'cuota' => $i,
                            'amount' => number_format($amountCuota, 2, '.', ''),
                            'expiredate' => $date,
                            'moneda_id' => $this->moneda_id,
                            'sucursal_id' => $this->sucursal->id,
                            'user_id' => auth()->user()->id,
                        ]);
                        $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
                    }
                } else {
                    $this->addError('countcuotas', 'Ingrese cantidad válida de cuotas');
                }
            }

            $seriecomprobante->contador = $numeracion;
            $seriecomprobante->save();

            Carshoop::with(['carshoopseries'])->ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)->each(function ($carshoop) {
                    $carshoop->carshoopseries()->delete();
                    $carshoop->delete();
                });
            DB::commit();
            $this->resetValidation();
            $this->dispatchBrowserEvent('toast', toastJSON('Venta registrado correctamente'));
            if (auth()->user()->hasPermissionTo('admin.ventas.edit')) {
                return redirect()->route('admin.ventas.edit', $venta);
            }
            return redirect()->route('admin.ventas');
            // $this->reset();
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
            if (count($carshoop->carshoopitems) > 0) {
                foreach ($carshoop->carshoopitems as $carshoopitem) {
                    $stockCombo = $carshoopitem->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;
                    $carshoopitem->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
                        'cantidad' => $stockCombo + $carshoop->cantidad,
                    ]);
                    $carshoopitem->delete();
                }
            }

            if ($carshoop->promocion) {
                $carshoop->promocion->outs = $carshoop->promocion->outs - $carshoop->cantidad;
                $carshoop->promocion->save();
            }

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
            DB::commit();
            $this->setTotal();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updategratis(Carshoop $carshoop)
    {
        $carshoop->gratuito = $carshoop->gratuito == 1 ? 0 : 1;
        $carshoop->save();
        $this->setTotal();
        $this->dispatchBrowserEvent('updated');
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
                $carshoopserie->carshoop->subtotal = $carshoopserie->carshoop->price * $carshoopserie->carshoop->cantidad;
                $carshoopserie->carshoop->total = $carshoopserie->carshoop->price * $carshoopserie->carshoop->cantidad;
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
            DB::commit();
            $this->setTotal();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getGRE()
    {

        $this->searchgre = mb_strtoupper(trim($this->searchgre), "UTF-8");
        $this->validate([
            'searchgre' => ['required', 'string', 'min:6', 'max:13']
        ]);

        DB::beginTransaction();
        try {
            $guia = Guia::with(['tvitems', 'client', 'guiable'])->where('seriecompleta', $this->searchgre)
                ->where('sucursal_id', $this->sucursal->id)->withTrashed()->first();
            if ($guia) {
                // dd($guia);
                if ($guia->trashed()) {
                    $mensaje =  response()->json([
                        'title' => 'GUÍA DE REMISIÓN SE ENCUENTRA ANULADO !',
                        'text' => "Guía de remisión se encuentra anulado, ingrese una nueva guia de remisión."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                if ($guia->guiable) {
                    $mensaje =  response()->json([
                        'title' => 'GRE YA SE ENCUENTRA RELACIONADO A UN COMPROBANTE !',
                        'text' => "Guía de remisión se encuentra vinculado al comprobante " . $guia->guiable->seriecompleta . "."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                }

                $existsreservados = $guia->tvitems()->where('alterstock', Almacen::RESERVAR_STOCK)->exists();
                if ($existsreservados) {
                    $mensaje =  response()->json([
                        'title' => 'GRE CONTIENE ITEMS PENDIENTES POR CONFIRMAR !',
                        'text' => "Guía de remisión contiene items reservados, pendientes por confirmar, verifique el estado de los items y vuelva a intentarlo."
                    ])->getData();
                    $this->dispatchBrowserEvent('validation', $mensaje);
                    return false;
                } else {

                    $tvitems = $guia->tvitems()->where('alterstock', Almacen::DISMINUIR_STOCK)->get();
                    if (count($tvitems) > 0) {
                        $this->sincronizegre = true;
                        $this->guiaremision = $guia;
                        $this->document = $guia->client->document;
                        $this->name = $guia->client->name;
                        if (count($guia->client->direccions) > 0) {
                            $this->direccion = $guia->client->direccions()->first()->name;
                        }

                        $this->confirmaradditemsventa($tvitems);
                        $this->dispatchBrowserEvent('toast', toastJSON('Items de GRE agregados correctamente'));
                        $this->setTotal();
                    } else {
                        $this->addError('searchgre', 'No se encontraron items del comprobante.');
                    }
                }
            } else {
                $this->addError('searchgre', 'No se encontraron resultados.');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function confirmaradditemsventa($tvitems)
    {
        foreach ($tvitems as $item) {
            $date = now('America/Lima');
            $precios = GetPrice::getPriceProducto($item->producto, mi_empresa()->usarlista() ? $this->pricetype_id : null)->getData();

            if ($precios->success) {
                if (mi_empresa()->uselistprice) {
                    if (!$precios->existsrango) {
                        $mensaje =  response()->json([
                            'title' => 'RANGO DE PRECIO DEL PRODUCTO NO DISPONIBLE !',
                            'text' => "No se encontraron rangos de precio de compra del producto."
                        ])->getData();
                        $this->dispatchBrowserEvent('validation', $mensaje);
                        return false;
                    }
                    if ($this->moneda->code == 'USD') {
                        $price =  $precios->pricewithdescountDolar ??  $precios->priceDolar;
                    } else {
                        $price = !is_null($precios->pricemanual) ? $precios->pricemanual : $precios->pricewithdescount ?? $precios->pricesale;
                    }
                } else {
                    if ($this->moneda->code  == 'USD') {
                        $price = $precios->pricewithdescountDolar ?? $precios->priceDolar;
                    } else {
                        $price = $precios->pricewithdescount ?? $precios->pricesale;
                    }
                }
            } else {
                $mensaje =  response()->json([
                    'title' => 'NO SE PUDO OBTENER PRECIO DEL PRODUCTO !',
                    'text' => "No se encontraron registros de precios del producto seleccionado $precios->error"
                ])->getData();
                $this->dispatchBrowserEvent('validation', $mensaje);
                return false;
            }

            $carshoop = Carshoop::create([
                'date' => $date,
                'cantidad' =>  $item->cantidad,
                'pricebuy' => $item->producto->pricebuy,
                'price' => $price,
                'igv' => 0,
                'subtotal' => $price * $item->cantidad,
                'total' => $price * $item->cantidad,
                'gratuito' => 0,
                'status' => 0,
                'producto_id' => $item->producto_id,
                'almacen_id' => $item->almacen_id,
                'moneda_id' => $this->moneda_id,
                'user_id' => auth()->user()->id,
                'sucursal_id' => $this->sucursal->id,
                'mode' => Almacen::NO_ALTERAR_STOCK,
                'cartable_type' => Venta::class,
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

    public function deleteallcarshoop()
    {
        try {
            DB::beginTransaction();
            Carshoop::with(['carshoopseries'])->ventas()->where('user_id', auth()->user()->id)
                ->where('sucursal_id', auth()->user()->sucursal_id)->each(function ($carshoop) {
                    $this->delete($carshoop);
                    // $carshoop->carshoopseries()->delete();
                    // $carshoop->delete();
                });
            DB::commit();
            $this->setTotal();
            $this->resetValidation();
            $this->dispatchBrowserEvent('toast', toastJSON('Carrito de ventas eliminado correctamente'));
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function desvinculargre()
    {
        $this->reset(['searchgre', 'sincronizegre', 'guiaremision']);
    }

    public function getClient()
    {

        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required', 'numeric', new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/', in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->document);

        if ($response->getData()) {
            if ($response->getData()->success) {
                // dd($response->getData());
                $this->resetValidation(['client_id', 'document', 'name', 'direccion']);
                $this->name = $response->getData()->name;
                $this->client_id = $response->getData()->client_id;
                if (!empty($response->getData()->direccion)) {
                    $this->direccion = $response->getData()->direccion;
                }

                if ($this->empresa->usarLista()) {
                    if ($response->getData()->pricetype_id) {
                        $this->pricetypeasigned = $response->getData()->pricetypeasigned;
                        $this->dispatchBrowserEvent('setPricetypeId', $response->getData()->pricetype_id);
                    }
                }

                if ($response->getData()->birthday) {
                    $this->dispatchBrowserEvent('birthday', $response->getData()->name);
                }
            } else {
                $this->resetValidation(['document']);
                $this->addError('document', $response->getData()->message);
            }
        }
    }

    public function getTransport()
    {

        $this->ructransport = trim($this->ructransport);
        $this->validate([
            'ructransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric',
                'digits:11',
                'regex:/^\d{11}$/',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
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
                'nullable',
                Rule::requiredIf($this->incluyeguia),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                $this->incluyeguia && $this->motivotraslado->code == '03' ? 'different:document' : '',
                // 'different:document',
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

    public function getDriver()
    {

        $this->documentdriver = trim($this->documentdriver);
        $this->validate([
            'documentdriver' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02'),
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/'
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->documentdriver, false);
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
}
