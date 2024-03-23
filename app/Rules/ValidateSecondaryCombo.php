<?php

namespace App\Rules;

use App\Models\Promocion;
use Illuminate\Contracts\Validation\Rule;

class ValidateSecondaryCombo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $value = empty($value) ? null : $value;
        $promocions = Promocion::disponibles()->where('producto_id', $value);

        return $promocions->exists() === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Producto definido como principal en combo existente activo.';
    }
}
