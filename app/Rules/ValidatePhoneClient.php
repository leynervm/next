<?php

namespace App\Rules;

use App\Models\Client;
use Illuminate\Contracts\Validation\Rule;

class ValidatePhoneClient implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     * @param integer $client_id
     * @param string $phone
     * @param integer $ignoreId
     */
    protected $client_id;
    protected $phone;
    protected $ignoreId;

    public function __construct($client_id, $phone, $ignoreId = null)
    {
        $this->client_id = $client_id;
        $this->phone = $phone;
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
        $query = Client::find($this->client_id)->telephones()->where('phone', $this->phone);
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
        return 'El número telefónico ya está registrado para este cliente.';
    }
}
