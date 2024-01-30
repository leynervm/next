<?php

namespace App\Rules;

use App\Models\Combo;
use App\Models\Promocion;
use Illuminate\Contracts\Validation\Rule;

class ValidatePrincipalCombo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $producto_id, $type;

    public function __construct($producto_id, $type)
    {
        $this->type = $type;
        $this->producto_id = $producto_id;
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
        if ($this->type == Promocion::DESCUENTO) {
            $disponibles = Promocion::where('type', Promocion::DESCUENTO)->activos()
                ->where('producto_id', $this->producto_id);
        } else {
            $disponibles = Promocion::whereIn('type', [Promocion::COMBO, Promocion::REMATE])->activos()
                ->where('producto_id', $this->producto_id);
        }

        return $disponibles->exists() === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Producto principal contiene promociones activas.';
    }
}
