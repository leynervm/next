<?php

namespace App\Traits;

use Carbon\Carbon;
use Luecano\NumeroALetras\NumeroALetras;

trait GenerarComprobante
{

    public function createComprobante()
    {
        $leyenda = new NumeroALetras();
        $currency_leyenda = 'NUEVOS SOLES';
        if ($this->moneda->isDolar()) {
            $currency_leyenda = 'DÓLARES';
        }
        return $this->comprobante()->create([
            'date' => $this->date,
            'expire' => Carbon::parse($this->date)->format('Y-m-d'),  //NO BIENE DE VENTA
            'seriecompleta' => $this->seriecompleta,
            'direccion' => $this->direccion,
            'observaciones' => $this->observaciones,
            'exonerado' => number_format($this->exonerado, 3, '.', ''),
            'gravado' => number_format($this->gravado, 3, '.', ''),
            'gratuito' => number_format($this->gratuito, 3, '.', ''),
            'inafecto' => number_format($this->inafecto, 3, '.', ''),
            'descuento' => number_format($this->descuento, 3, '.', ''),
            'otros' => number_format($this->otros, 3, '.', ''),
            'igv' => number_format($this->igv, 3, '.', ''),
            'igvgratuito' => number_format($this->igvgratuito, 3, '.', ''),
            'subtotal' => number_format($this->subtotal, 3, '.', ''),
            'total' => number_format($this->total, 3, '.', ''),
            'paymentactual' => number_format($this->paymentactual, 3, '.', ''),
            'percent' => $this->sucursal->empresa->igv,  //NO BIENE DE VENTA
            'referencia' => $this->seriecompleta,
            'leyenda' => $leyenda->toInvoice($this->total, 2, $currency_leyenda),  //NO BIENE DE VENTA
            'sendmode' => $this->sucursal->empresa->sendmode, //NO BIENE DE VENTA
            'client_id' => $this->client_id,
            'typepayment_id' => $this->typepayment_id,
            'seriecomprobante_id' => $this->seriecomprobante_id,
            'moneda_id' => $this->moneda_id,
            'sucursal_id' => $this->sucursal_id,
            'user_id' => auth()->user()->id,
        ]);
    }
}
