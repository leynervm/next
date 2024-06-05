<?php

namespace App\Traits;

use App\Models\Cajamovimiento;

trait CajamovimientoTrait
{
    public function savePayment($sucursal_id, $amount, $totalamount, $tipocambio, $moneda_id, $methodpayment_id, $typemovement, $concept_id, $openbox_id, $monthbox_id, $referencia, $detalle)
    {
        return Cajamovimiento::create([
            'date' => now('America/Lima'),
            'amount' => number_format($amount, 4, '.', ''),
            'totalamount' => number_format($totalamount, 4, '.', ''),
            'tipocambio' => number_format($tipocambio, 4, '.', ''),
            'moneda_id' => $moneda_id,
            'methodpayment_id' => $methodpayment_id,
            'typemovement' => $typemovement,
            'concept_id' => $concept_id,
            'openbox_id' => $openbox_id,
            'monthbox_id' => $monthbox_id,
            'referencia' => $referencia,
            'detalle' => trim($detalle),
            'sucursal_id' => $sucursal_id,
            'user_id' => auth()->user()->id,
            'cajamovimientable_id' => $this->id,
            'cajamovimientable_type' => get_class($this),
        ]);
    }

    public function deletePayment($cajamovimiento_id)
    {
        $cajamovimiento = Cajamovimiento::find($cajamovimiento_id);
        if ($cajamovimiento) {
            $cajamovimiento_id->delete();
        }
    }
}
