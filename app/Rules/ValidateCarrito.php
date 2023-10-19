<?php

namespace App\Rules;

use App\Models\Carshoop;
use Illuminate\Contracts\Validation\Rule;

class ValidateCarrito implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $mensaje, $moneda_id;

    public function __construct($moneda)
    {
        $this->moneda_id = $moneda;
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
        $carrito = Carshoop::select('moneda_id')->Micarrito();

        if ($carrito->get()->count() > 0) {
            $countmoneda = $carrito->groupBy('moneda_id');

            if ($countmoneda->get()->count() > 1) {
                $this->mensaje = 'Existen items en el carrito de compras con distintos tipos de moneda.';
            } else {
                if ($countmoneda->first()->moneda_id == $this->moneda_id ) {
                    return true;
                }
                else {
                    $this->mensaje = 'Tipo de moneda de venta diferente a los items del carrito de compras.';
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
