<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateNumericEquals implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $comparar;

    public function __construct($comparar)
    {
        $this->comparar = $comparar;
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
        // $comparar = (float)($this->comparar);
        return (float)($value) == (float)($this->comparar);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute debe ser igual a " . ($this->comparar);
    }
}
