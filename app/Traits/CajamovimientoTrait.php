<?php

namespace App\Traits;

use App\Models\Cajamovimiento;

trait CajamovimientoTrait
{


    /**
     * @savePayment Registrar  movimiento en caja.
     * @param float $amount Monto original del movimiento( PEN o USD )
     * @param string $totalamount Monto de afectación en caja, se usa conversión en caso de ser moneda distinta a origen (Monto de visualización)  
     * @param string $tipocambio Cuando la moneda de origen es diferente a moneda de pago ingresar TC
     * @return string $moneda_id Tipo de moneda de pago que reflejará y afectará en caja
     */

    public function savePayment($sucursal_id, $amount, $totalamount, $tipocambio, $moneda_id, $methodpayment_id, $typemovement, $concept_id, $openbox_id, $monthbox_id, $referencia, $detalle = null)
    {
        return Cajamovimiento::create([
            'date' => now('America/Lima'),
            'amount' => number_format($amount, 3, '.', ''),
            'totalamount' => number_format($totalamount, 3, '.', ''),
            'tipocambio' => number_format($tipocambio, 3, '.', ''),
            'moneda_id' => $moneda_id,
            'methodpayment_id' => $methodpayment_id,
            'typemovement' => $typemovement,
            'concept_id' => $concept_id,
            'openbox_id' => $openbox_id,
            'monthbox_id' => $monthbox_id,
            'referencia' => $referencia,
            'detalle' => !empty($detalle) ? trim($detalle) : null,
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
