<?php

namespace App\Rules;

use App\Models\Sucursal;
use Illuminate\Contracts\Validation\Rule;

class ValidateTypecomprobante implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $sucursal_id;

    public function __construct($sucursal_id)
    {
        $this->sucursal_id = $sucursal_id;
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

        $exists = Sucursal::find($this->sucursal_id)->seriecomprobantes()
            ->withWhereHas('typecomprobante', function ($query) use ($value) {
                $query->where('id', $value);
            })->exists();

        return !$exists;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Tipo de comprobante ya se encuentra asignado.';
    }
}
