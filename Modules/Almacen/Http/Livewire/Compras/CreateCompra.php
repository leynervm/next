<?php

namespace Modules\Almacen\Http\Livewire\Compras;

use App\Models\Concept;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Typepayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;

class CreateCompra extends Component
{

    public $sucursal, $typepayment, $methodpayment, $moneda, $opencaja;
    public $date, $proveedor_id, $moneda_id, $referencia, $guia,
    $tipocambio, $typepayment_id, $detalle;
    public $methodpayment_id, $cuenta_id, $concept;

    public $cuentas = [];

    public $exonerado = 0;
    public $gravado = 0;
    public $igv = 0;
    public $otros = 0;
    public $descuento = 0;
    public $total = 0;
    public $counter = 0;

    protected function rules()
    {
        return [
            'sucursal.id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'proveedor_id' => ['required', 'integer', 'min:1', 'exists:proveedors,id'],
            'date' => ['required', 'date'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'tipocambio' => [
                'nullable', Rule::requiredIf($this->moneda->code == 'USD'),
            ],
            'referencia' => [
                'required', 'string', 'min:3'
            ],
            'guia' => [
                'nullable', 'string', 'min:3'
            ],
            'gravado' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
            'exonerado' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
            'igv' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
            'descuento' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
            'otros' => [
                'required', 'numeric', 'min:0', 'decimal:0,4'
            ],
            'total' => [
                'required', 'numeric', 'gt:0', 'decimal:0,4'
            ],
            'typepayment_id' => [
                'required', 'integer', 'min:1', 'exists:typepayments,id'
            ],
            'methodpayment_id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:methodpayments,id'
            ],
            'concept.id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:concepts,id'
            ],
            'opencaja.id' => [
                'nullable',
                Rule::requiredIf($this->typepayment->paycuotas == 0),
                'integer', 'min:1', 'exists:opencajas,id'
            ],
            'cuenta_id' => [
                'nullable',
                Rule::requiredIf(count($this->cuentas) > 0),
                'integer', 'min:1', 'exists:concepts,id'
            ],
            'detalle' => ['nullable', 'string'],
            'counter' => ['required', 'integer', 'min:0'],
        ];
    }

    public function mount(Sucursal $sucursal, Typepayment $typepayment, Methodpayment $methodpayment, Moneda $moneda, Concept $concept, Opencaja $opencaja)
    {
        $this->sucursal = $sucursal;
        $this->methodpayment = $methodpayment;
        $this->moneda = $moneda;
        $this->concept = $concept;
        $this->opencaja = $opencaja;
        $this->moneda_id = $moneda->id ?? null;
        $this->typepayment_id = $typepayment->id ?? null;
        $this->methodpayment_id = $methodpayment->id ?? null;
        // $this->typepayment = new Typepayment();
        $this->typepayment = $typepayment;
    }

    public function render()
    {
        $proveedores = Proveedor::orderBy('name', 'asc')->get();
        $monedas = Moneda::all();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        return view('almacen::livewire.compras.create-compra', compact('proveedores', 'typepayments', 'monedas', 'methodpayments'));
    }

    public function save()
    {


        // dd($this->typepayment->paycuotas);
        $this->calculartotal();
        if ($this->typepayment_id) {
            $this->typepayment = Typepayment::find($this->typepayment_id);
        }


        $validateData = $this->validate();
        DB::beginTransaction();
        try {
            $compra = Compra::create($validateData);
            if ($this->typepayment->paycuotas == 0) {
                $compra->cajamovimiento()->create([
                    'date' => now('America/Lima'),
                    'amount' => $this->total,
                    'referencia' => $this->referencia,
                    'detalle' => trim($this->detalle),
                    'moneda_id' => $this->moneda_id,
                    'methodpayment_id' => $this->methodpayment_id,
                    'typemovement' => 'EGRESO',
                    'cuenta_id' => $this->cuenta_id,
                    'concept_id' => $this->concept->id,
                    'opencaja_id' => $this->opencaja->id,
                    'sucursal_id' => $this->sucursal->id,
                    'user_id' => auth()->user()->id,
                ]);
            }
            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['sucursal', 'moneda', 'concept', 'opencaja', 'typepayment']);
            return redirect()->route('admin.almacen.compras.show', $compra);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updatedMonedaId($value)
    {
        $this->reset(['tipocambio']);
        $this->moneda = Moneda::find($value);
    }

    // public function updatedGravado($value)
    // {
    //     $this->gravado = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
    //     $this->calculartotal();
    // }

    // public function updatedExonerado($value)
    // {
    //     $this->exonerado = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
    //     $this->calculartotal();
    // }

    // public function updatedIgv($value)
    // {
    //     $this->igv = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
    //     $this->calculartotal();
    // }

    // public function updatedOtros($value)
    // {
    //     $this->otros = number_format(trim($value) == "" ? 0 : $value, 4, '.', '');
    //     $this->calculartotal();
    // }

    public function calculartotal()
    {
        $this->total = number_format($this->gravado + $this->igv + $this->exonerado + $this->otros, 4, '.', '');
    }

    // public function updatedTypepaymentId($value)
    // {
    //     $this->reset(['cuenta_id', 'cuentas', 'methodpayment_id']);
    //     if ($value) {
    //         $this->typepayment = Typepayment::find($value);
    //     }
    // }

    public function updatedMethodpaymentId($value)
    {
        $this->reset(['cuenta_id', 'cuentas']);
        if ($value) {
            $this->methodpayment_id = $value;
            $this->methodpayment = Methodpayment::with('cuentas')->find($value);
            $this->cuentas = $this->methodpayment->cuentas ?? [];
            if ($this->methodpayment->cuentas()->count() == 1) {
                $this->cuenta_id = $this->methodpayment->cuentas()->first()->id ?? null;
            }
        }

    }

    // public function hydrate()
    // {
    //     $this->dispatchBrowserEvent('render-select2-compra');
    // }
}
