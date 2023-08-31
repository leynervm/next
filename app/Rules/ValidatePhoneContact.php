<?php

namespace App\Rules;

use App\Models\Contact;
use Illuminate\Contracts\Validation\Rule;

class ValidatePhoneContact implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $contact_id;
    protected $phone;
    protected $ignoreId;

    public function __construct($contact_id, $phone, $ignoreId = null)
    {
        $this->contact_id = $contact_id;
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
        $query = Contact::find($this->contact_id)->telephones()->where('phone', $this->phone);
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
        return 'El número telefónico ya está registrado para este contacto.';
    }
}
