<?php

namespace App\Rules;

use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use Illuminate\Contracts\Validation\Rule;

class ValidateSeriecomprobanteSucursal implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $sucursal_id, $column, $code;

    public function __construct($sucursal_id, $column = 'seriecomprobante_id',  $code = null)
    {
        $this->sucursal_id = $sucursal_id;
        $this->column = $column;
        $this->code = $code;
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

        if ($this->column == "typecomprobante_id") {
            $exists = Sucursal::find($this->sucursal_id)->seriecomprobantes()
                ->where('typecomprobante_id', $value)
                ->where('code', $this->code)
                ->exists();
        } else {
            $exists = Sucursal::find($this->sucursal_id)->seriecomprobantes()
                ->where('seriecomprobante_id', $value)
                ->exists();
        }

        return $exists === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute ya se encuentra registrado.';
    }
}
