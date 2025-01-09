<?php

namespace App\Http\Livewire\Modules\Marketplace\Usersweb;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CreateUserweb extends Component
{

    use PasswordValidationRules, AuthorizesRequests;

    public $open = false;
    public $exists = false;

    public $document, $name, $email, $password, $password_confirmation;

    protected function rules()
    {
        return [
            'document' => [
                'required',
                'numeric',
                'regex:/^\d{8}(?:\d{3})?$/',
                new ValidateDocument(),
                new CampoUnique('users', 'document', null, true)
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
        $this->authorize('admin.marketplace.userweb.create');
        if ($this->open == false) {
            $this->reset();
            $this->resetValidation();
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.marketplace.userweb.create');
        $this->document = trim($this->document);
        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->validate();
        DB::beginTransaction();
        try {
            User::create([
                'document' => $this->document,
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'password_confirmation' => $this->password_confirmation,
            ]);
            DB::commit();
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
            $this->dispatchBrowserEvent('created');
            $this->emitTo('modules.marketplace.usersweb.show-usersweb', 'render');
            $this->resetValidation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function limpiaruserweb()
    {
        $this->reset(['document', 'name', 'exists']);
    }

    public function searchclient()
    {
        $this->authorize('admin.marketplace.userweb.create');
        $this->resetValidation(['name']);
        $this->document = trim($this->document);
        $this->validate([
            'document' => ['required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/', new CampoUnique('employers', 'document', null, true)],
        ]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->document,
            'searchbd' => true,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->name = $cliente->name;
                $this->exists = true;
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $this->name = '';
                $this->exists = false;
                $this->addError('document', $cliente->error);
            }
        } else {
            $mensaje =  response()->json([
                'title' => 'Error:' . $response->status() . ' ' . $response->json(),
                'text' => null
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
            return false;
        }
    }
}
