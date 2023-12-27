<?php

namespace App\Rules;

use App\Models\Carshoop;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Session;

class ValidateCarrito implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $mensaje, $moneda_id, $sucursal_id;

    public function __construct($moneda, $sucursal_id)
    {
        $this->moneda_id = $moneda;
        $this->sucursal_id = $sucursal_id;
        $this->mensaje = 'No existen items en el carrito de compras.';
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        $carrito = Session::get('carrito', []);
        if (!is_array($carrito)) {
            $carrito = json_decode($carrito, true);
        }

        if (count($carrito) > 0) {
            $carritoJson = json_decode(Session::get('carrito'));
            $monedaEquals = true;
            $sucursalEquals = true;

            foreach ($carritoJson as $item) {
                if ($item->moneda_id !== $this->moneda_id) {
                    $monedaEquals = false;
                    $this->mensaje = 'Carrito de compras con items distintos a la moneda seleccionada.';
                }

                if ($item->sucursal_id !== $this->sucursal_id) {
                    $monedaEquals = false;
                    $this->mensaje = 'Carrito de compras con items distintos a la sucursal en uso.';
                }
            }

            return $monedaEquals;
        }

        // $carrito = Carshoop::select('moneda_id')->Micarrito()
        //     ->where('sucursal_id', $this->sucursal_id);

        // if ($carrito->get()->count() > 0) {
        //     $countmoneda = $carrito->groupBy('moneda_id');

        //     if ($countmoneda->get()->count() > 1) {
        //         $this->mensaje = 'Existen items en el carrito de compras con distintos tipos de moneda.';
        //     } else {
        //         if ($countmoneda->first()->moneda_id == $this->moneda_id) {
        //             return true;
        //         } else {
        //             $this->mensaje = 'Tipo de moneda de venta diferente a los items del carrito de compras.';
        //         }
        //     }
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->mensaje;
    }
}
