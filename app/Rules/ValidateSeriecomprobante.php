<?php

namespace App\Rules;

use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use Illuminate\Contracts\Validation\Rule;

class ValidateSeriecomprobante implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $sucursal_id, $mensaje;

    public function __construct($sucursal_id = null)
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
        if ($this->sucursal_id) {
            $query = Sucursal::find($this->sucursal_id)->seriecomprobantes()
                ->where('seriecomprobantes.id',  $value)->exists();
        } else {
            $query = Seriecomprobante::find($value)->sucursals()->exists();
        }

        return !$query;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Serie del comprobante ya se encuentra asignado.';
    }
}
