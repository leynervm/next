<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Almacen\Entities\Producto;

class ValidateSerieRequerida implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $producto_id;
    protected $almacen_id;
    protected $serie;
    protected $ignoreId;

    public function __construct($producto_id, $almacen_id, $serie, $ignoreId = null)
    {
        $this->producto_id = $producto_id;
        $this->almacen_id = $almacen_id;
        $this->serie = $serie;
        $this->ignoreId = $ignoreId;
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
        $query = Producto::find($this->producto_id)->seriesdisponibles();

        if (!is_null($this->almacen_id)) {
            $query->where('almacen_id', $this->almacen_id);
        }
        if (trim($value) !== "") {
            $query->where('serie', trim(mb_strtoupper($value, "UTF-8")));
        }

        return $query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Serie del producto no se encuentra disponible.';
    }
}
