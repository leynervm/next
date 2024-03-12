<?php

namespace App\Rules;

use App\Models\Client;
use Illuminate\Contracts\Validation\Rule;

class ValidateDireccion implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $client_id;
    protected $ignoreId;

    public function __construct($client_id, $ignoreId = null)
    {
        $this->client_id = $client_id;
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

        $query = Client::find($this->client_id)->direccions()
            ->whereRaw('UPPER(name) = ?', trim(mb_strtoupper($value, "UTF-8")));

        if (!is_null($this->ignoreId)) {
            $query->where('id', '<>', $this->ignoreId);
        }
        return !$query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Dirección del cliente ya está registrado.';
    }
}
