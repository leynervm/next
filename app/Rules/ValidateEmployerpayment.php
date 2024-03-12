<?php

namespace App\Rules;

use App\Models\Employer;
use App\Models\Employerpayment;
use Illuminate\Contracts\Validation\Rule;

class ValidateEmployerpayment implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $employer_id, $month;

    public function __construct($employer_id, $month)
    {
        $this->employer_id = $employer_id;
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
        $employer = Employer::with(['employerpayments'])->find($this->employer_id);

        // $query = $employer->employerpayments()->withWhereHas('cajamovimientos', function ($query) {
        //     $query->where('monthbox_id', $this->monthbox_id);
        // })->exists();

        $query = $employer->employerpayments()->where('month', $this->month)->exists();

        return !$query === true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ya existe un pago mensual correspondiente al mes de la caja.';
    }
}
