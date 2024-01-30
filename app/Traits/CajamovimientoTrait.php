<?php

namespace App\Traits;

use App\Models\Cajamovimiento;

/**
 * 
 */
trait CajamovimientoTrait
{
    public function saveMovimiento($amount, $referencia, $detalle, $moneda_id, $methodpayment_id, $typemovement, $cuenta_id, $concept_id, $opencaja_id, $sucursal_id)
    {
        Cajamovimiento::create([
            'date' => now('America/Lima'),
            'amount' => $amount,
            'referencia' => $referencia,
            'detalle' => trim($detalle),
            'moneda_id' => $moneda_id,
            'methodpayment_id' => $methodpayment_id,
            'typemovement' => 'EGRESO',
            'cuenta_id' => $cuenta_id,
            'concept_id' => $concept_id,
            'opencaja_id' => $opencaja_id,
            'sucursal_id' => $sucursal_id,
            'user_id' => auth()->user()->id,
            'cajamovimientable_id' => $this->id,
            'cajamovimientable_type' => get_class($this),
        ]);
    }

    public function deleteMovimiento()
    {

    }
}

