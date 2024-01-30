<?php

namespace App\Http\Livewire\Modules\Almacen\Compras;

use App\Models\Cajamovimiento;
use App\Models\Concept;
use App\Models\Cuota;
use App\Models\Empresa;
use App\Models\Kardex;
use App\Models\Methodpayment;
use App\Models\Moneda;
use App\Models\Opencaja;
use App\Models\Proveedor;
use App\Models\Typepayment;
use App\Rules\ValidateNumericEquals;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Compra;

class ShowCompra extends Component
{

    public $compra;
    public $open = false;

    public $paymentactual = 0;
    public $pendiente = 0;
    public $opencaja, $methodpayment_id, $concept_id, $detalle;

    protected function rules()
    {
        return [
            'compra.proveedor_id' => ['required', 'integer', 'min:1', 'exists:proveedors,id'],
            'compra.sucursal_id' => ['required', 'integer', 'min:1', 'exists:sucursals,id'],
            'compra.date' => ['required', 'date'],
            'compra.moneda_id' => ['required', 'integer', 'min:1', 'exists:monedas,id'],
            'compra.referencia' => ['required', 'string', 'min:3'],
            'compra.tipocambio' => [
                'nullable', Rule::requiredIf($this->compra->moneda->code == 'USD'),
            ],
            'compra.guia' => ['nullable', 'string', 'min:3'],
            'compra.gravado' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.exonerado' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.igv' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.descuento' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.otros' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'compra.total' => ['required', 'numeric', 'gt:0', 'decimal:0,4'],
            'compra.typepayment_id' => ['required', 'integer', 'min:1', 'exists:typepayments,id'],
            'compra.detalle' => ['nullable', 'string'],
            'compra.counter' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
        ];
    }

    public function mount(Compra $compra, Opencaja $opencaja)
    {
        $this->compra = $compra;
        $this->opencaja = $opencaja;
        $this->pendiente = $compra->total - $this->compra->cajamovimientos()->sum('amount');
    }

    public function render()
    {
        $methodpayments = Methodpayment::orderBy('name', 'asc')->get();
        return view('livewire.modules.almacen.compras.show-compra', compact('methodpayments'));
    }

    public function calculartotal()
    {
        $this->compra->total = number_format(($this->compra->gravado + $this->compra->igv + $this->compra->exonerado + $this->compra->otros) - $this->compra->descuento, 4, '.', '');
    }

    public function update()
    {
        $validateData = $this->validate();
        $this->calculartotal();
        DB::beginTransaction();
        try {
            $this->compra->save();

            if ($this->compra->cajamovimiento) {
                $this->compra->cajamovimiento->amount = $this->compra->total;
                $this->compra->cajamovimiento->save();
            }
            DB::commit();
            $this->compra->refresh();
            $this->resetValidation();
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete()
    {

        if ($this->compra->compraitems->count() > 0) {
            $seriesouts = $this->compra->compraitems()
                ->WhereHas('series', function ($query) {
                    $query->whereNotNull('dateout');
                })->count();

            if ($seriesouts) {
                $message = response()->json([
                    'title' => 'No se puede eliminar el registro seleccionado !',
                    'text' => 'Existen series relacionados en salidas, eliminarlo causará conflictos en los datos'
                ])->getData();
                $this->dispatchBrowserEvent('validation', $message);
                return false;
            }
        }

        if ($this->compra->cuotas()->withWhereHas('cajamovimiento')->count() > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar la compra seleccionada',
                'text' => "La compra contiene cuotas de pago realizadas, eliminar pagos manualmente e inténtelo nuevamente."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }

        DB::beginTransaction();
        try {
            $this->compra->compraitems()->each(function ($compraitem) {

                $productoAlmacen = $compraitem->producto->almacens()
                    ->where('almacen_id', $compraitem->almacen_id)->first();

                // $compraitem->deleteKardex($compraitem->id);
                $compraitem->saveKardex(
                    $this->compra->sucursal_id,
                    $compraitem->producto_id,
                    $compraitem->almacen_id,
                    $productoAlmacen->pivot->cantidad,
                    $productoAlmacen->pivot->cantidad - $compraitem->cantidad,
                    $compraitem->cantidad,
                    '-',
                    Kardex::SALIDA_ANULACION_COMPRA,
                    $this->compra->referencia
                );

                $compraitem->producto->almacens()->updateExistingPivot($compraitem->almacen_id, [
                    'cantidad' => $productoAlmacen->pivot->cantidad - $compraitem->cantidad,
                ]);
                $compraitem->producto->pricebuy = $compraitem->oldpricebuy;
                $compraitem->producto->pricesale = $compraitem->oldpricesale;
                $compraitem->producto->save();
                $compraitem->series()->forceDelete();
                $compraitem->forceDelete();
            });

            if ($this->compra->cajamovimientos()->count() > 0) {
                $this->compra->cajamovimientos()->delete();
            }
            $this->compra->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
            return redirect()->route('admin.almacen.compras');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function openmodal()
    {
        $this->pendiente = $this->compra->total - $this->compra->cajamovimientos()->sum('amount');
        $this->paymentactual = $this->pendiente;
        $this->open = true;
    }

    public function savepayment()
    {
        if (!verifyOpencaja($this->opencaja->id)) {
            $this->dispatchBrowserEvent('validation', getMessageOpencaja());
            return false;
        }

        $this->concept_id = Concept::Compra()->first()->id;

        $this->validate([
            'paymentactual' => ['required', 'numeric', 'min:1', 'decimal:0,4', 'lte:' . $this->pendiente, 'regex:/^\d{0,8}(\.\d{0,4})?$/',],
            'opencaja.id' => ['required', 'integer', 'min:1', 'exists:opencajas,id'],
            'concept_id' => ['required', 'integer', 'min:1', 'exists:concepts,id'],
            'methodpayment_id' => ['required', 'integer', 'min:1', 'exists:methodpayments,id'],
            'detalle' => ['nullable'],
        ]);

        DB::beginTransaction();
        try {
            $this->compra->cajamovimientos()->create([
                'date' => now('America/Lima'),
                'amount' => number_format($this->paymentactual, 4, '.', ''),
                'referencia' => $this->compra->referencia,
                'detalle' => trim($this->detalle),
                'moneda_id' => $this->compra->moneda_id,
                'methodpayment_id' => $this->methodpayment_id,
                'typemovement' => Cajamovimiento::EGRESO,
                'concept_id' => $this->concept_id,
                'opencaja_id' => $this->opencaja->id,
                'sucursal_id' => $this->compra->sucursal->id,
                'user_id' => auth()->user()->id,
            ]);

            DB::commit();
            $this->resetValidation();
            $this->reset(['open', 'methodpayment_id', 'detalle', 'paymentactual']);
            $this->compra->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function deletepay(Cajamovimiento $cajamovimiento)
    {

        DB::beginTransaction();
        try {
            $cajamovimiento->delete();
            DB::commit();
            $this->compra->refresh();
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
