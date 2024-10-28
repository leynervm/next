<?php

namespace App\Actions\Fortify;

use App\Models\Client;
use App\Models\Pricetype;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\Recaptcha;
use App\Rules\ValidateDocument;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {

        Validator::make($input, [
            'document' => [
                'required',
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                new ValidateDocument(),
                new CampoUnique('users', 'document', null, true)
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new CampoUnique('users', 'email', null, true)],
            'password' => $this->passwordRules(),
            'g-recaptcha-response' => ['required', new Recaptcha("v2")], //TENER CUIDADO NOMBRE TIENE QUE MARCAR NO AUTO
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'document' => trim($input['document']),
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
