<?php

namespace App\Traits;

use Carbon\Carbon;

trait RegistrarCuotas
{

    /**
     * @generarCuotas Registrar cuotas de pago.
     * @param float $totalAmountCuotas Monto a generar cuotas
     * @param integer $countcuotas N° cuotas a generar para guardar  
     */

    public function registrarCuotas($totalAmountCuotas, $countcuotas = 1,)
    {

        $date = Carbon::now('America/Lima')->addMonth()->format('Y-m-d');
        $sumaCuotas = 0.00;
        $amountCuota = number_format($totalAmountCuotas / $countcuotas, 3, '.', '');

        for ($i = 1; $i <= $countcuotas; $i++) {
            $sumaCuotas = number_format($sumaCuotas + $amountCuota, 2, '.', '');
            if ($i == $countcuotas) {
                $result = number_format($totalAmountCuotas - $sumaCuotas, 2, '.', '');
                $amountCuota = number_format($amountCuota + ($result), 2, '.', '');
            }

            $this->cuotas()->create([
                'cuota' => $i,
                'amount' => number_format($amountCuota, 2, '.', ''),
                'expiredate' => $date,
                'moneda_id' => $this->moneda_id,
                'sucursal_id' => $this->sucursal_id,
                'user_id' => auth()->user()->id,
            ]);
            $date = Carbon::parse($date)->addMonth()->format('Y-m-d');
        }
    }
}
