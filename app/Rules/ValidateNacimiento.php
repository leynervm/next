<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidateNacimiento implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     * @param  integer  $years
     */

    protected $years;

    public function __construct($years = 10)
    {
        $this->years = $years;
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
        // Convierte el valor a una instancia de Carbon
        $date = Carbon::createFromFormat('Y-m-d', $value);
        $yearsAgo = now()->subYears($this->years);
        return $date->lte($yearsAgo);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "La fecha debe ser al menos $this->years años atrás.";
    }
}
