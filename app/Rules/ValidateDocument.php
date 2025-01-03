<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateDocument implements Rule
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
        $document = trim($value);

        if (is_numeric($document)) {
            return strlen($document) === 8 || strlen($document) === 11;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "El :attribute ingresado es incorrecto";
    }
}
