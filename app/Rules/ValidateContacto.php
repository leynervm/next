<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateContacto implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $campo_condicional;

    public function __construct($campo_condicional)
    {
        $this->campo_condicional = $campo_condicional;
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

        return strlen(trim($this->campo_condicional)) == 11 && trim($value) == "" ? false : true;

        // if (strlen($this->campo_condicional) === 11 && empty($value)) {
        //     $fail('');
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute es requerido.';
    }
}
