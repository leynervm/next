<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Almacen\Entities\Producto;

class ValidateStock implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $producto_id, $almacen_id;

    public function __construct($producto_id, $almacen_id)
    {
        $this->producto_id = $producto_id;
        $this->almacen_id = $almacen_id;
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
        $query = Producto::find($this->producto_id)->almacens()
            ->find($this->almacen_id)->pivot->cantidad;;

        return $query > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Stock no disponible en almacén seleccionado.';
    }
}
