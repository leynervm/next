<?php

namespace App\Http\Livewire\Modules\Marketplace\Usersweb;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Livewire\Component;

class CreateUserweb extends Component
{

    use PasswordValidationRules;

    public $open = false;

    public $document, $name, $email, $password, $password_confirmation;

    protected function rules()
    {
        return [
            'document' => [
                'required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/',
                new ValidateDocument(), new CampoUnique('users', 'document', null, true)
            ],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', new CampoUnique('users', 'email', null, true)],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    public function render()
    {
        return view('livewire.modules.marketplace.usersweb.create-userweb');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->reset();
            $this->resetValidation();
        }
    }

    public function save()
    {
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->validate();
        User::create([
            'document' => $this->document,
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'password_confirmation' => $this->password_confirmation,
        ]);
        $this->dispatchBrowserEvent('toast', toastJSON('Usuario web registrado correctamente'));
        $this->emitTo('modules.marketplace.usersweb.show-usersweb', 'render');
        $this->reset();
        $this->resetValidation();
    }
}
