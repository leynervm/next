<?php

namespace App\Traits;

use App\Models\Cajamovimiento;

trait CajamovimientoTrait
{
    public function savePayment($sucursal_id, $amount, $moneda_id, $methodpayment_id, $typemovement, $concept_id, $openbox_id, $monthbox_id, $referencia, $detalle)
    {
        Cajamovimiento::create([
            'date' => now('America/Lima'),
            'amount' => number_format($amount, 4, '.', ''),
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

        // $methodpayment = Methodpayment::find($methodpayment_id)->type;
        // $openbox = Openbox::find($openbox_id);

        // if ($methodpayment == Methodpayment::EFECTIVO) {
        //     if ($typemovement == MovimientosEnum::INGRESO->value) {
        //         $openbox->totalcash = $openbox->totalcash + number_format($amount, 4, '.', '');
        //     } else {
        //         $openbox->totalcash = $openbox->totalcash - number_format($amount, 4, '.', '');
        //     }
        // } else {
        //     if ($typemovement == MovimientosEnum::INGRESO->value) {
        //         $openbox->totaltransfer = $openbox->totaltransfer + number_format($amount, 4, '.', '');
        //     } else {
        //         $openbox->totaltotaltransfercash = $openbox->totaltransfer - number_format($amount, 4, '.', '');
        //     }
        // }
        // $openbox->save();
    }

    public function deletePayment($cajamovimiento_id)
    {
        $cajamovimiento = Cajamovimiento::find($cajamovimiento_id);
        if ($cajamovimiento) {
            $cajamovimiento_id->delete();
        }
    }
}
