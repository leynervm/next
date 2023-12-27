<?php

namespace App\Rules;

use App\Models\Client;
use App\Models\Proveedor;
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
    protected $table, $id, $ignoreId;

    public function __construct($table, $id, $ignoreId = null)
    {
        $this->table = $table;
        $this->id = $id;
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
        if ($this->table == 'clients') {
            $query = Client::find($this->id)->telephones()->where('phone', trim($value));
        } else {
            $query = Proveedor::find($this->id)->telephones()->where('phone', trim($value));
        }

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
        $table = $this->table == 'clients' ? 'cliente' : 'proveedor';
        return "Teléfono del $table ya está existe.";
    }
}
