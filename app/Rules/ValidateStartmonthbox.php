<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidateStartmonthbox implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $month;

    public function __construct($month = null)
    {
        $this->month = $month;
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
        // if ($this->month !== null) {
        $fechaInicio = Carbon::parse($value);
        $primerDiaMes = Carbon::parse($this->month)->firstOfMonth();

        if ($fechaInicio->lessThan($primerDiaMes)) {
            return false;
        }

        return true;
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "El campo :attribute no debe ser menor al mes seleccionado.";
    }
}
