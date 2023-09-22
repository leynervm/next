<?php

namespace App\Rules;

use App\Helpers\FormatoPersonalizado;
use Illuminate\Contracts\Validation\Rule;

class ValidateFileKey implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $extRequired;

    public function __construct($extRequired)
    {
        $this->extRequired = $extRequired;
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
        $ext = FormatoPersonalizado::getExtencionFile($value->getClientOriginalName());
        return $ext === $this->extRequired;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Solo se permiten archivos con extención (.' . $this->extRequired . ')';
    }
}
