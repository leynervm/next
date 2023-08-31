<?php

namespace App\Rules;

use App\Models\Client;
use Illuminate\Contracts\Validation\Rule;

class ValidateContactClient implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $client_id;
    protected $document;
    protected $ignoreId;

    public function __construct($client_id, $document, $ignoreId = null)
    {
        $this->client_id = $client_id;
        $this->document = $document;
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
        $query = Client::find($this->client_id)->contacts()->where('document', $this->document);
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
        return 'El contacto ya está registrado para este cliente.';
    }
}
