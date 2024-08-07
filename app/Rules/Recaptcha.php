<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $version;


    public function __construct($version = "v3")
    {
        $this->version = $version;
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
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config($this->version == "v3" ? 'services.recaptcha_v3.key_secret' : 'services.recaptcha_v2.key_secret'),
            'response' => $value
        ])->object();

        if ($this->version == "v3") {
            return $response->success && $response->score >= 0.5;
        } else {
            return $response->success;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The verification of Recaptcha failed.');
    }
}
