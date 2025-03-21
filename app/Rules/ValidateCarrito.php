<?php

namespace App\Rules;

use App\Models\Tvitem;
use Illuminate\Contracts\Validation\Rule;

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
        $carrito = Tvitem::ventas()->micart()->select('moneda_id');

        if ($carrito->get()->count() > 0) {
            $monedas = $carrito->groupBy('moneda_id');

            if ($monedas->get()->count() > 1) {
                $this->mensaje = 'Carrito de venta con diferenetes monedas.';
            } else {
                if ($monedas->first()->moneda_id == $this->moneda_id) {
                    return true;
                } else {
                    $this->mensaje = 'Moneda seleccionada diferente al carrito de ventas.';
                }
            }
        }
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
