<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Typepayment;
use App\Rules\ValidateReferenciaCompra;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;

class CreateCompra extends Component
{

    use AuthorizesRequests;

    public $moneda;
    public $date, $proveedor_id, $moneda_id, $referencia, $guia,
        $sucursal_id, $tipocambio, $typepayment_id, $detalle;

    public $exonerado = 0;
    public $gravado = 0;
    public $igv = 0;
    public $otros = 0;
    public $descuento = 0;
    public $subtotal = 0;
    public $total = 0;

    protected function rules()
    {
        return [
            'sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'proveedor_id' => ['required', 'integer', 'min:1', 'exists:proveedors,id'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'tipocambio' => ['nullable', Rule::requiredIf($this->moneda->code == 'USD'), 'regex:/^\d{0,3}(\.\d{0,3})?$/'],
            'referencia' => [
                'required', 'string', 'min:6', 'regex:/^[a-zA-Z][a-zA-Z0-9][0-9]{2}-(?!0+$)\d{1,8}$/',
                new ValidateReferenciaCompra($this->proveedor_id, $this->sucursal_id)
            ],
            'guia' => ['nullable', 'string', 'min:6', 'regex:/^[a-zA-Z][a-zA-Z0-9][0-9]{2}-(?!0+$)\d{1,8}$/'],
            'gravado' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'exonerado' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'igv' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'descuento' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'otros' => ['required', 'numeric', 'min:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'total' => ['required', 'numeric', 'gt:0', 'decimal:0,4', 'regex:/^\d{0,8}(\.\d{0,4})?$/'],
            'typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'detalle' => ['nullable', 'string'],
        ];
    }

    public function mount()
    {
        $this->sucursal_id = auth()->user()->sucursal_id;
        $this->moneda = new Moneda();
    }

    public function render()
    {
        $proveedores = Proveedor::orderBy('name', 'asc')->get();
        $monedas = Moneda::all();
        $typepayments = Typepayment::orderBy('name', 'asc')->get();
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();

        if (auth()->user()->isAdmin()) {
            $sucursals = Sucursal::orderBy('codeanexo', 'asc')->get();
        } else {
            $sucursals = auth()->user()->sucursal()->get();
        }

        return view('livewire.modules.almacen.compras.create-compra', compact('proveedores', 'typepayments', 'monedas', 'methodpayments', 'sucursals'));
    }

    public function save()
    {

        $this->authorize('admin.almacen.compras.create');
        if ($this->moneda_id) {
            $this->moneda = Moneda::find($this->moneda_id);
        }

        $validateData = $this->validate();
        DB::beginTransaction();
        try {
            $compra = Compra::create($validateData);
            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->resetValidation();
            $this->resetExcept(['moneda']);
            return redirect()->route('admin.almacen.compras.edit', $compra);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
