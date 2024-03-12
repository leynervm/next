<?php

namespace App\Rules;

use App\Models\Monthbox;
use Illuminate\Contracts\Validation\Rule;

class ValidateMonthbox implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $sucursals, $ignoreId;

    public function __construct($sucursals = [], $ignoreId = null)
    {
        $this->sucursals = $sucursals;
        $this->ignoreId = $ignoreId;
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
        $query = Monthbox::where('month', $value)
        ->whereNotIn('status',[ Monthbox::CERRADO]);

        if (count($this->sucursals) > 0) {
            $query->whereIn('sucursal_id', $this->sucursals);
        }

        if (!is_null($this->ignoreId)) {
            $query->where('id', '<>', $this->ignoreId);
        }

        return !$query->exists() === true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campo :attribute ya está en uso.';
    }
}
