<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Producto;

class ValidateStock implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $producto_id, $almacen_id, $cantidad, $mensaje;

    public function __construct($producto_id, $almacen_id, $cantidad = null)
    {
        $this->producto_id = $producto_id;
        $this->almacen_id = $almacen_id;
        $this->cantidad = $cantidad;
        $this->mensaje = 'Stock no disponible en almacén seleccionado.';
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

        $producto = Producto::with(['unit', 'almacens'])->find($this->producto_id);
        $query = $producto->almacens()->find($this->almacen_id)->pivot->cantidad;

        $this->mensaje = $this->cantidad > 0
            ? "Cantidad supera al stock disponible [" . formatDecimalOrInteger($query) . " " . $producto->unit->name. "]."
            : "Stock no disponible en almacén seleccionado [" . formatDecimalOrInteger($query) . " " . $producto->unit->name. "].";
        $query = $this->cantidad > 0 ? $query - $this->cantidad : $query;
        return $this->cantidad > 0 ? $query >= 0 : $query > 0;

        // if ($this->cantidad > 0) {
        //     $query = $query - $this->cantidad;
        //     $this->mensaje = "Cantidad supera al stock disponible [].";
        //     return $query >= 0;
        // } else {
        //     return $query > 0;
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
