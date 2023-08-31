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
    protected $name;
    protected $ignoreId;

    public function __construct($client_id, $name, $ignoreId = null)
    {
        $this->client_id = $client_id;
        $this->name = $name;
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

        $query = Client::find($this->client_id)->direccions()->where('name', $this->name);
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
        return 'La dirección ya está registrado para este cliente.';
    }
}
