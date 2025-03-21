<?php

namespace App\Rules;

use App\Enums\PromocionesEnum;
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
        if ($this->type != PromocionesEnum::COMBO->value) {
            $promocions = Promocion::where('status', Promocion::ACTIVO)
                ->where('producto_id', $this->producto_id)
                ->where('type', '<>', PromocionesEnum::COMBO->value)
                ->availables()->disponibles()->exists();
            if ($promocions) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Producto cuenta con promociones activas.';
    }
}
