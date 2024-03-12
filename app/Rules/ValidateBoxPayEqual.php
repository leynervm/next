<?php

namespace App\Rules;

use App\Models\Employerpayment;
use App\Models\Monthbox;
use Illuminate\Contracts\Validation\Rule;

class ValidateBoxPayEqual implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $employerpayment_id;

    public function __construct($employerpayment_id)
    {
        $this->employerpayment_id = $employerpayment_id;
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
        $employerpayment = Employerpayment::find($this->employerpayment_id);
        $monthbox = Monthbox::find($value);

        return $employerpayment->month == $monthbox->month;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mes de pago no coincide con la caja mensual activa .';
    }
}
