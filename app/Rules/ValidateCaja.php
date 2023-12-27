<?php

namespace App\Rules;

use App\Models\Caja;
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
        $sucursals = auth()->user()->sucursalDefault()->select('sucursals.id')->pluck('sucursals.id');
        // return Opencaja::cajasAbiertas()->CajasUser()->count() === 0;
        return Opencaja::whereHas('caja', function ($query) use ($sucursals) {
            $query->whereIn('sucursal_id', $sucursals);
        })->cajasAbiertas()->CajasUser()->count() === 0;
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
