<?php

namespace App\Http\Livewire\Modules\Ventas\Ventas;

use App\Helpers\GetClient;
use App\Models\Cajamovimiento;
use App\Models\Client;
use App\Models\Concept;
use App\Models\Empresa;
use App\Models\Methodpayment;
use App\Models\Modalidadtransporte;
use App\Models\Moneda;
use App\Models\Motivotraslado;
use App\Models\Opencaja;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Serie;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Typecomprobante;
use App\Models\Typepayment;
use App\Models\Ubigeo;
use App\Rules\ValidateCarrito;
use App\Rules\ValidateDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Ventas\Entities\Venta;
use Nwidart\Modules\Facades\Module;

class ShowResumenVenta extends Component
{

    public $empresa, $sucursal, $typepayment, $methodpayment, $moneda, $modalidadtransporte, $motivotraslado;
    public $cotizacion_id, $client_id, $direccion_id, $pricetype_id, $mensaje;
    public $moneda_id, $opencaja, $methodpayment_id, $typepayment_id, $detallepago, $concept, $cuenta_id;
    public $typecomprobante, $typecomprobante_id, $seriecomprobante_id, $tribute_id;
    public $document, $name, $direccion, $pricetypeasigned;

    public $serieguiaremision_id, $ructransport, $nametransport,
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

    public $accounts = [];
    public $cuotas = [];
    public $items = [];

    public $regionorigen_id, $provinciaorigen_id, $distritoorigen_id;
    public $regiondestino_id, $provinciadestino_id, $distritodestino_id;

    public $provinciasorigen = [];
    public $distritosorigen = [];
    public $provinciasdestino = [];
    public $distritosdestino = [];
    public $arrayequalremite = ['02', '04', '07'];
    public $arraydistintremite = ['01', '03', '05', '06', '09', '14', '17'];
    // protected $listeners = ['render' => 'refresh', 'deleteitem', 'deleteserie'];

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
            // 'client_id' => ['nullable', 'integer', 'min:1', 'exists:clients,id'],
            'typecomprobante_id' => ['required', 'integer', 'min:1', 'exists:typecomprobantes,id'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'typepayment.id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'paymentactual' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 1),
                'numeric', 'min:0',
                $this->typepayment->paycuotas ? 'lt:' . $this->total : '',
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
            'opencaja.id' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:opencajas,id',
            ],
            'methodpayment_id' => [
                'nullable', Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:methodpayments,id',
            ],
            'cuenta_id' => [
                'nullable', Rule::requiredIf($this->methodpayment->cuentas->count() > 1),
                'integer', 'min:1', 'exists:cuentas,id',
            ],
            'detallepago' => ['nullable'],
            'cotizacion_id' => ['nullable', 'integer', 'min:1', 'exists:cotizacions,id'],
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'seriecomprobante_id' => ['required', 'integer', 'min:1', 'exists:seriecomprobantes,id'],
            'items' => ['required', 'array', 'min:1', new ValidateCarrito($this->moneda->id, $this->sucursal->id)],
            'ructransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
                $this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false ? 'different:empresa.document' : '',
            ],
            'nametransport' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
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
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:6'
            ],
            'lastname' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:6'
            ],
            'licencia' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:9', 'max:10'
            ],
            'placa' => [
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02' && $this->vehiculosml == false),
                'string', 'min:6', 'max:8'
            ],
            'peso' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'numeric', 'gt:0', 'decimal:0,4',
            ],
            'packages' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1',
            ],
            'datetraslado' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'date', 'after_or_equal:today',
            ],
            'placavehiculo' => ['nullable', 'string', 'min:6', 'max:8'],
            'note' => ['nullable', 'string', 'min:10'],
            'regionorigen_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'exists:ubigeos,departamento_inei'
            ],
            'provinciaorigen_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'exists:ubigeos,provincia_inei'
            ],
            'distritoorigen_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:ubigeos,id'
            ],
            'ubigeoorigen_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:ubigeos,id'
            ],
            'direccionorigen' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'min:12',
            ],
            'regiondestino_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'exists:ubigeos,departamento_inei'
            ],
            'provinciadestino_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'exists:ubigeos,provincia_inei'
            ],
            'distritodestino_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:ubigeos,id'
            ],
            'ubigeodestino_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:ubigeos,id'
            ],
            'direcciondestino' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'string', 'min:12',
            ],
            'motivotraslado_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:motivotraslados,id'
            ],
            'modalidadtransporte_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:modalidadtransportes,id'
            ],
            'serieguiaremision_id' => [
                'nullable', Rule::requiredIf($this->incluyeguia),
                'integer', 'min:1', 'exists:seriecomprobantes,id'
            ],
        ];
    }

    public function mount(Empresa $empresa, Sucursal $sucursal, Typepayment $typepayment, Methodpayment $methodpayment, Seriecomprobante $seriecomprobante, Moneda $moneda, Concept $concept, Opencaja $opencaja)
    {
        $this->motivotraslado = new Motivotraslado();
        $this->modalidadtransporte = new Modalidadtransporte();
        $this->empresa = $empresa;
        $this->sucursal = $sucursal;
        $this->methodpayment = $methodpayment;
        $this->typepayment = $typepayment;
        $this->seriecomprobante_id = $seriecomprobante->id ?? null;
        $this->typecomprobante = $seriecomprobante->typecomprobante;
        $this->concept = $concept;
        $this->opencaja = $opencaja;

        $this->methodpayment_id = $methodpayment->id ?? null;
        $this->typepayment_id = $typepayment->id ?? null;
        $this->typecomprobante_id = $seriecomprobante->typecomprobante_id ?? null;
        $this->moneda_id = $moneda->id ?? null;
        $this->moneda = $moneda;
        $this->setTotal();
        // $this->total = Carshoop::Micarrito()->where('sucursal_id', $this->sucursal->id)->where('gratuito', 0)->sum('total') ?? 0;
        // $this->exonerado = $this->total;
        // $this->gratuito = Carshoop::Micarrito()->where('sucursal_id', $this->sucursal->id)->where('gratuito', 1)->sum('total') ?? 0;

        $ubigeosucursal = Ubigeo::find($this->sucursal->ubigeo_id);
        $this->regionorigen_id = $ubigeosucursal->departamento_inei;
        $this->provinciaorigen_id = $ubigeosucursal->provincia_inei;
        $this->distritoorigen_id = $ubigeosucursal->id;
        $this->direccionorigen = $this->sucursal->direccion;
        $this->loadprovinciasorigen($this->regionorigen_id);
        $this->distritosorigen = Ubigeo::select('id', 'distrito')->where('provincia_inei', $this->provinciaorigen_id)->groupBy('id', 'distrito')->orderBy('distrito', 'asc')->get();
    }

    public function render()
    {

        if (Module::isEnabled('Facturacion')) {
            $typecomprobantes = Typecomprobante::SucursalTypecomprobantes()
                ->orderBy('code', 'asc')->get();
            $modalidadtransportes = Modalidadtransporte::orderBy('code', 'asc')->get();
            $motivotraslados = Motivotraslado::whereIn('code', ['01', '03'])->orderBy('code', 'asc')->get();
        } else {
            $typecomprobantes = Typecomprobante::DefaultSucursalTypecomprobantes()
                ->orderBy('code', 'asc')->get();
            $modalidadtransportes = [];
            $motivotraslados = [];
        }

        // $carrito = Carshoop::Micarrito()->with(['carshoopseries', 'producto', 'almacen'])
        //     ->where('sucursal_id', $this->sucursal->id)->orderBy('id', 'asc')->get();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        $monedas = Moneda::orderBy('currency', 'asc')->get();
        $regiones = Ubigeo::select('departamento_inei', 'region')->groupBy('departamento_inei', 'region')->orderBy('region', 'asc')->get();

        $carrito = Session::get('carrito', []);
        if (!is_array($carrito)) {
            $carrito = json_decode($carrito);
        }

        return view('livewire.modules.ventas.ventas.show-resumen-venta', compact('carrito', 'typecomprobantes', 'typepayments', 'methodpayments', 'monedas', 'regiones', 'modalidadtransportes', 'motivotraslados'));
    }


    public function updatedIncluyeigv($value)
    {
        $this->reset(['gravado', 'exonerado', 'igv']);
        $this->incluyeigv = $value == 1 ? 1 : 0;
        $this->setTotal();
    }

    public function setTotal()
    {

        $resumen = json_decode(getTotalCarrito('carrito'));
        $total = $resumen->total;
        $this->gratuito = $resumen->gratuito;
        // $total = Carshoop::Micarrito()->where('sucursal_id', $this->sucursal->id)->where('gratuito', 0)->sum('total') ?? 0;
        // $this->gratuito = Carshoop::Micarrito()->where('sucursal_id', $this->sucursal->id)->where('gratuito', 1)->sum('total') ?? 0;

        $totalSinIncrement = number_format($total - $this->paymentactual ?? 0, 4, '.', '');
        $this->amountincrement = (($totalSinIncrement ?? 0) * $this->increment) / 100;
        $this->total = number_format($total + (($totalSinIncrement * $this->increment ?? 0) / 100), 4, '.', '');

        if ($this->incluyeigv) {
            $this->igv = number_format(($this->total * $this->empresa->igv) / (100 + $this->empresa->igv), 4, '.', '');
            $this->gravado = number_format($this->total - $this->igv, 4, '.', '');
        } else {
            $this->exonerado = number_format($this->total, 4, '.', '');
        }
    }

    public function updatedMonedaId($value)
    {
        if ($value) {
            $this->moneda = Moneda::findOrFail($value);
            $this->dispatchBrowserEvent('setMoneda', $value);
        }
    }

    public function updatedTypepaymentId($value)
    {
        $this->reset(['increment', 'paymentactual', 'amountincrement', 'gravado', 'exonerado', 'igv', 'countcuotas', 'cuotas', 'accounts', 'cuenta_id']);
        if ($value) {
            $this->setTotal();
        }
    }

    public function updatedIncrement($value)
    {
        $this->increment = !empty($value) ? $value : 0;
        $this->setTotal();
    }

    public function updatedPaymentactual($value)
    {
        $this->paymentactual = !empty($value) ? $value : 0;
        $this->setTotal();
        $this->resetValidation(['paymentactual']);
    }

    public function updatedMethodpaymentId($value)
    {
        $this->reset(['accounts', 'cuenta_id']);
        $this->methodpayment_id = !empty(trim($value)) ? trim($value) : null;
        if ($this->methodpayment_id) {
            $this->methodpayment = Methodpayment::findOrFail($value);
            $this->accounts = $this->methodpayment->cuentas;
            if ($this->methodpayment->cuentas->count() == 1) {
                $this->cuenta_id = $this->methodpayment->cuentas->first()->id;
            }
        }
    }

    public function save()
    {

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

        // $carrito = Carshoop::Micarrito()->where('sucursal_id', $this->sucursal->id)->get();
        // $this->items = $carrito;

        $carrito = Session::get('carrito', []);
        $this->items = (!is_array($carrito)) ? (json_decode($carrito, true)) : $carrito;

        if ($this->incluyeguia) {
            $guiaremision = Typecomprobante::facturables()->where('code', '09')->first();
            $series = $this->sucursal->seriecomprobantes()->where('typecomprobante_id', $guiaremision->id);

            if ($series->exists()) {
                $serieguiaremision = $series->first();
                $this->serieguiaremision_id = $serieguiaremision->id ?? null;
                $numeracionguia = $serieguiaremision->contador + 1;
            }

            $this->ubigeoorigen_id = $this->distritoorigen_id;
            $this->ubigeodestino_id = $this->distritodestino_id;
        }

        if ($this->typecomprobante_id) {

            $this->typecomprobante = Typecomprobante::find($this->typecomprobante_id);
            $this->setTotal();
            $series = $this->sucursal->seriecomprobantes()->where('typecomprobante_id', $this->typecomprobante_id);

            if ($series->exists()) {
                $seriecomprobante = $series->first();
                $this->seriecomprobante_id = $seriecomprobante->id ?? null;
                $numeracion = $seriecomprobante->contador + 1;
            }
        }

        $validatedData = $this->validate();
        $this->getClient(false);
        $this->getTransport();
        DB::beginTransaction();

        try {

            $client = Client::where('document', $this->document)->first();
            if (!$client) {
                $client = Client::create([
                    'document' => $this->document,
                    'name' => $this->name,
                    'sexo' => strlen(trim($this->document)) == 11 ? 'E' : null,
                    'pricetype_id' => Pricetype::DefaultPricetype()->first()->id ?? null,
                ]);
            }

            $client->direccions()->updateOrCreate([
                'name' => $this->direccion,
            ]);

            $carritoSum = json_decode(getTotalCarrito('carrito'));
            $totalSinIncrement = number_format($carritoSum->sumatoria - $this->paymentactual ?? 0, 4, '.', '');
            // $totalSinIncrement = number_format($carrito->sum('total') - $this->paymentactual ?? 0, 4, '.', '');
            $totalAmountCuotas = number_format($totalSinIncrement + (($totalSinIncrement * $this->increment ?? 0) / 100), 2, '.', '');
            $amountCuota = number_format($totalAmountCuotas / $this->countcuotas, 4, '.', '');
            $priceIncrItem = number_format(($totalAmountCuotas - $totalSinIncrement) / count(getCarrito()), 4, '.', '');
            // $priceIncrItem = number_format(($totalAmountCuotas - $totalSinIncrement) / count($carrito), 4, '.', '');
            $gratuito = number_format($this->incluyeigv ? ($this->gratuito * 100) / (100 + $this->empresa->igv) : $this->gratuito, 4, '.', '');
            $igvgratuito = number_format($this->incluyeigv ? $this->gratuito - $gratuito : 0, 4, '.', '');
            //ESTABA ANTES DE CAMBIAR CARRITO COMENTADO YA $totalAmount = number_format($this->total + (($totalSinIncrement * $this->increment ?? 0) / 100), 4, '.', '');
            $venta = Venta::create([
                'date' => now('America/Lima'),
                'code' => 'VT',
                'direccion' => $this->direccion,
                'exonerado' => number_format($this->exonerado, 4, '.', ''),
                'gravado' => number_format($this->gravado, 4, '.', ''),
                'gratuito' => number_format($gratuito, 4, '.', ''),
                'inafecto' => number_format($this->inafecto, 4, '.', ''),
                'descuento' => number_format($this->descuentos, 4, '.', ''),
                'otros' => number_format($this->otros, 4, '.', ''),
                'igv' => number_format($this->igv, 4, '.', ''),
                'igvgratuito' => number_format($igvgratuito, 4, '.', ''),
                'subtotal' => number_format($this->gravado + $this->exonerado + $this->inafecto, 4, '.', ''),
                'total' => number_format($this->total, 4, '.', ''),
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

            if (!$this->typepayment->paycuotas || $this->paymentactual > 0) {
                $venta->cajamovimiento()->create([
                    'date' => now('America/Lima'),
                    'amount' => number_format($this->paymentactual > 0 ? $this->paymentactual : $this->total, 4, '.', ''),
                    'referencia' => 'VT-' . $venta->id,
                    'detalle' => trim($this->detallepago),
                    'moneda_id' => $this->moneda_id,
                    'methodpayment_id' => $this->methodpayment_id,
                    'typemovement' => Cajamovimiento::INGRESO,
                    'cuenta_id' => $this->cuenta_id,
                    'concept_id' => $this->concept->id,
                    'opencaja_id' => $this->opencaja->id,
                    'sucursal_id' => $this->sucursal->id,
                    'user_id' => auth()->user()->id,
                ]);
            }

            if (Module::isEnabled('Facturacion')) {
                if ($seriecomprobante->typecomprobante->sendsunat) {
                    $comprobante = $venta->comprobante()->create([
                        'seriecompleta' => $seriecomprobante->serie . '-' . $numeracion,
                        'date' => Carbon::now('America/Lima'),
                        'expire' => Carbon::now('America/Lima')->format('Y-m-d'),
                        'direccion' => $this->direccion,
                        'exonerado' => number_format($this->exonerado, 4, '.', ''),
                        'gravado' => number_format($this->gravado, 4, '.', ''),
                        'gratuito' => number_format($gratuito, 4, '.', ''),
                        'inafecto' => number_format($this->inafecto, 4, '.', ''),
                        'descuento' => number_format($this->descuentos, 4, '.', ''),
                        'otros' => number_format($this->otros, 4, '.', ''),
                        'igv' => number_format($this->igv, 4, '.', ''),
                        'igvgratuito' => number_format($igvgratuito, 4, '.', ''),
                        'subtotal' => number_format($this->gravado + $this->exonerado + $this->inafecto, 4, '.', ''),
                        'total' => number_format($this->total, 4, '.', ''),
                        'paymentactual' => number_format($this->typepayment->paycuotas ? $this->paymentactual : $this->total, 4, '.', ''),
                        'percent' => $this->empresa->igv,
                        'referencia' => 'VT-' . $venta->id,
                        'leyenda' => 'SON SOLES/100',
                        'client_id' => $client->id,
                        'typepayment_id' => $this->typepayment_id,
                        'seriecomprobante_id' => $this->seriecomprobante_id,
                        'moneda_id' => $this->moneda_id,
                        'sucursal_id' => $this->sucursal->id,
                        'user_id' => auth()->user()->id,
                    ]);
                }
            }

            if ($this->incluyeguia) {
                $guia = $comprobante->guias()->create([
                    'seriecompleta' => $serieguiaremision->serie . '-' . $numeracionguia,
                    'date' => Carbon::now('America/Lima'),
                    'expire' => Carbon::now('America/Lima')->format('Y-m-d'),
                    'code' => $serieguiaremision->typecomprobante->code,
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
                    'seriecomprobante_id' => $this->serieguiaremision_id,
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

            $counter = 1;
            $carritoJSON = json_decode(Session::get('carrito'));

            foreach ($carritoJSON as $car) {

                $producto = Producto::with('almacens')->find($car->producto_id);
                $stock = $producto->almacens->find($car->almacen_id)->pivot->cantidad;

                if ($stock && $stock > 0) {
                    if ($stock - $car->cantidad < 0) {
                        $this->addError('items', 'Cantidad del producto ' . $producto->name . ' supera el stock disponible [' . formatDecimalOrInteger($stock) . '].');
                        DB::rollBack();
                        return false;
                    } else {

                        $porcentajeIncr = number_format(100 * ($priceIncrItem / $car->price), 2, '.', '');
                        $price = number_format($car->price + $priceIncrItem, 4, '.', '');
                        $pricesale = $this->incluyeigv ? ($price * 100) / (100 + $this->empresa->igv) : $price;
                        $igv = $this->incluyeigv ? $price - $pricesale : 0;
                        $subtotalItemIGV = $this->incluyeigv ? $igv * $car->cantidad : 0;
                        $subtotalItem = number_format($pricesale * $car->cantidad, 4, '.', '');
                        $totalItem = number_format($pricesale * $car->cantidad, 4, '.', '');

                        $newTvitem = [
                            'date' => now('America/Lima'),
                            'cantidad' => $car->cantidad,
                            'pricebuy' => $car->pricebuy,
                            'price' => number_format($pricesale, 2, '.', ''),
                            'igv' => number_format($igv, 2, '.', ''),
                            'subtotaligv' => number_format($subtotalItemIGV, 2, '.', ''),
                            'subtotal' => number_format($subtotalItem, 2, '.', ''),
                            'total' => number_format($totalItem, 2, '.', ''),
                            'status' => 0,
                            'increment' => $porcentajeIncr,
                            'almacen_id' => $car->almacen_id,
                            'producto_id' => $car->producto_id,
                            'user_id' => auth()->user()->id
                        ];

                        $tvitem = $venta->tvitems()->create($newTvitem);

                        if ($this->incluyeguia) {
                            $tvitemguia = $guia->tvitems()->create($newTvitem);
                        }

                        if (count($car->series)) {
                            foreach ($car->series as $ser) {
                                $serieItem = Serie::find($ser->id);
                                if ($serieItem) {
                                    if ($serieItem->status == 2) {
                                        $this->addError('items', 'Serie: ' . $serieItem->serie . ', ya no se encuentra disponible.');
                                        DB::rollBack();
                                        return false;
                                    } else {
                                        $newTvserieitem = [
                                            'date' => now('America/Lima'),
                                            'status' => 0,
                                            'serie_id' => $ser->id,
                                            'user_id' => auth()->user()->id,
                                        ];

                                        $tvitem->itemseries()->create($newTvserieitem);
                                        if ($this->incluyeguia) {
                                            $tvitemguia->itemseries()->create($newTvserieitem);
                                        }
                                        $serieItem->status = 2;
                                        $serieItem->dateout = now('America/Lima');
                                        $serieItem->save();
                                    }
                                } else {
                                    $this->addError('items', 'No se pudo encontrar la serie: ' . $ser->serie . '.');
                                    DB::rollBack();
                                    return false;
                                }
                            }
                        }

                        if ($car->gratuito) {
                            $afectacion = $this->incluyeigv ? '13' : '21';
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
                                    'descripcion' => $producto->name,
                                    'code' => $producto->sku,
                                    'cantidad' => $car->cantidad,
                                    'price' => number_format($pricesale, 2, '.', ''),
                                    'igv' => number_format($igv, 2, '.', ''),
                                    'subtotaligv' => number_format($subtotalItemIGV, 2, '.', ''),
                                    'subtotal' => number_format($subtotalItem, 2, '.', ''),
                                    'total' => number_format($totalItem, 2, '.', ''),
                                    'unit' => $producto->unit->code,
                                    'codetypeprice' => $car->gratuito ? '02' : '01', //01: Precio unitario (incluye el IGV) 02: Valor referencial unitario en operaciones no onerosas 
                                    'afectacion' => $afectacion,
                                    'codeafectacion' => $car->gratuito ? '9996' : $codeafectacion,
                                    'nameafectacion' => $car->gratuito ? 'GRA' : $nameafectacion,
                                    'typeafectacion' => $car->gratuito ? 'FRE' : $typeafectacion,
                                    'abreviatureafectacion' => $car->gratuito ? 'Z' : $abreviatureafectacion,
                                    'percent' => $this->incluyeigv ? $this->empresa->igv : 0,
                                ]);
                            }
                        }

                        $producto->almacens()->updateExistingPivot($car->almacen_id, [
                            'cantidad' => formatDecimalOrInteger($stock) - $car->cantidad,
                        ]);
                    }
                } else {
                    $this->addError('items', 'Stock del producto ' . $producto->name . ' no disponible [' . formatDecimalOrInteger($stock) . '].');
                    DB::rollBack();
                    return false;
                }

                $counter++;
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
            DB::commit();
            Session::forget('carrito');
            $this->resetValidation();
            // $this->resetErrorBag();
            $this->reset();
            $this->dispatchBrowserEvent('created');
            return redirect()->route('admin.ventas.show', $venta);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        $carrito = Session::pull('carrito');
        if (!is_array($carrito)) {
            $carrito = json_decode($carrito, true);
            unset($carrito[$id]);
        }

        $carritoJSON = response()->json($carrito)->getContent();
        Session::put('carrito', $carritoJSON);
        $this->setTotal();
    }

    public function deleteserie($idItem, $idSerie)
    {
        $carritoSesion = Session::pull('carrito');
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
                }
            }

            if ($existSerie) {
                $carritoSesion[$idItem]['series'] = [];
                foreach ($arrayseries as $serie) {
                    $carritoSesion[$idItem]['series'][] = $serie;
                }
                $carritoSesion[$idItem]['cantidad'] -= 1;
            }
        }

        $carritoJSON = response()->json($carritoSesion)->getContent();
        Session::put('carrito', $carritoJSON);
        $this->setTotal();
    }

    public function deletecarrito()
    {
        Session::forget('carrito');
        $this->setTotal();
    }

    public function updategratis($id)
    {
        $carrito = Session::pull('carrito');
        if (!is_array($carrito)) {
            $carrito = json_decode($carrito, true);
            $carrito[$id]['gratuito'] = $carrito[$id]['gratuito'] == 0 ? 1 : 0;
        }

        $carritoJSON = response()->json($carrito)->getContent();
        Session::put('carrito', $carritoJSON);
        $this->setTotal();
    }

    // public function updategratis(Carshoop $carshoop)
    // {
    //     $carshoop->gratuito = $carshoop->gratuito == 1 ? 0 : 1;
    //     $carshoop->save();
    //     $this->setTotal();
    //     $this->dispatchBrowserEvent('updated');
    // }

    // public function deleteitem(Carshoop $carshoop)
    // {

    //     DB::beginTransaction();

    //     try {

    //         $stock = $carshoop->producto->almacens->find($carshoop->almacen_id)->pivot->cantidad;

    //         if ($carshoop->carshoopseries()->exists()) {
    //             foreach ($carshoop->carshoopseries as $itemserie) {
    //                 $itemserie->serie()->update([
    //                     'status' => 0,
    //                     'dateout' => null
    //                 ]);
    //             }
    //             $carshoop->carshoopseries()->delete();
    //         }

    //         $carshoop->producto->almacens()->updateExistingPivot($carshoop->almacen_id, [
    //             'cantidad' => $stock + $carshoop->cantidad,
    //         ]);

    //         $carshoop->delete();
    //         DB::commit();
    //         $this->setTotal();
    //         $this->emitTo('ventas.ventas.create-venta', 'render');
    //         $this->dispatchBrowserEvent('deleted');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    // public function deleteserie(Carshoopserie $carshoopserie)
    // {

    //     DB::beginTransaction();
    //     try {
    //         $cantidad = $carshoopserie->carshoop->cantidad - 1;
    //         $carshoopserie->serie->dateout = null;
    //         $carshoopserie->serie->status = 0;
    //         $carshoopserie->serie->save();
    //         $carshoopserie->carshoop->cantidad = $cantidad;
    //         $carshoopserie->carshoop->subtotal = $carshoopserie->carshoop->price * $cantidad;
    //         $carshoopserie->carshoop->save();
    //         $carshoopserie->forceDelete();


    //         // FALTA CODIGO DEVOLVER STOCK ALMACEN CUANDO ELIMINO UNA SERIE





    //         DB::commit();
    //         $this->setTotal();
    //         $this->dispatchBrowserEvent('deleted');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

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

    public function getClient($event = true)
    {

        $this->document = trim($this->document);
        $this->validate([
            'document' => [
                'required', 'numeric',
                $this->typecomprobante->code == "01" ? 'digits:11' : new ValidateDocument,
                'regex:/^\d{8}(?:\d{3})?$/',
                in_array($this->motivotraslado->code, $this->arraydistintremite) ? 'different:empresa.document' : (in_array($this->motivotraslado->code, $this->arrayequalremite) ? 'same:empresa.document' : ''),
            ],
        ]);

        $client = new GetClient();
        $response = $client->getClient($this->document);
        if ($response->getData()) {
            if ($response->getData()->success) {
                $this->resetValidation(['document', 'name', 'direccion']);
                $this->name = $response->getData()->name;
                if (!empty($response->getData()->direccion)) {
                    $this->direccion = $response->getData()->direccion;
                }

                // $this->pricetype_id = $response->getData()->pricetype_id;
                if ($event) {
                    $this->pricetypeasigned = $response->getData()->pricetypeasigned;
                    $this->dispatchBrowserEvent('setPricetypeId', $response->getData()->pricetype_id);
                } // if ($this->client->nacimiento) {
                //     $nacimiento = Carbon::parse($this->client->nacimiento)->format("d-m");
                //     $hoy = Carbon::now('America/Lima')->format("d-m");

                //     if ($nacimiento ==  $hoy) {
                //         $this->mensaje = "FELÍZ CUMPLEAÑOS";
                //     }
                // }

            } else {
                // dd($response);
                $this->resetValidation(['document']);
                $this->addError('document', $response->getData()->message);
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
                'nullable',
                Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '01' && $this->vehiculosml == false),
                'numeric', 'digits:11', 'regex:/^\d{11}$/',
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
                'nullable', Rule::requiredIf($this->incluyeguia),
                'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                $this->motivotraslado->code == '03' ? 'different:document' : '',
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
                'nullable', Rule::requiredIf($this->incluyeguia && $this->modalidadtransporte->code == '02'),
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

    public function hydrate()
    {
        $this->loadprovinciasorigen($this->regionorigen_id);
        $this->loadprovinciasdestino($this->regiondestino_id);
    }
}
