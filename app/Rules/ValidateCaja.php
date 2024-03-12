<?php

namespace App\Rules;

use App\Models\Caja;
use App\Models\Openbox;
use App\Models\Opencaja;
use Illuminate\Contracts\Validation\Rule;

class ValidateCaja implements Rule
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
        $query = Openbox::mybox(auth()->user()->sucursal_id);
        return !$query->exists() === true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El usuario ya cuenta con una caja aperturada.';
    }
}
