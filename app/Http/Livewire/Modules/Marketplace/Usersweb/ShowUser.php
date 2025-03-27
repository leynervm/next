<?php

namespace App\Http\Livewire\Modules\Marketplace\Usersweb;

use App\Models\Client;
use App\Models\User;
use App\Rules\CampoUnique;
use App\Rules\ValidateDocument;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class ShowUser extends Component
{

    use AuthorizesRequests;
    public $user, $client;

    public function mount(User $user)
    {
        $this->user = $user;
        if (empty($user->client)) {
            $client = Client::where('document', $user->document)->orWhere('email', $user->email)->first();
            if ($client) {
                $this->client = $client;
            }
        }
    }

    protected function rules()
    {
        return [
            'user.document' => ['required', 'numeric', 'regex:/^\d{8}(?:\d{3})?$/', new ValidateDocument(), new CampoUnique('users', 'document', $this->user->id, true)],
            'user.name' => ['required', 'string', 'min:3', 'max:255'],
            'user.email' => ['required', 'email', new CampoUnique('users', 'email', $this->user->id, true)],
        ];
    }

    public function render()
    {
        return view('livewire.modules.marketplace.usersweb.show-user');
    }

    public function update()
    {
        $this->authorize('admin.marketplace.userweb.edit');
        $this->user->name = trim($this->user->name);
        $this->validate();
        DB::beginTransaction();
        try {
            $this->user->save();
            DB::commit();
            $this->dispatchBrowserEvent('updated');
            $this->resetValidation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function searchclient()
    {
        $this->authorize('admin.marketplace.userweb.create');
        $this->resetValidation(['name']);
        $this->user->document = trim($this->user->document);
        $this->validate([
            'user.document' => ['required', 'numeric', 'digits_between:8,11', 'regex:/^\d{8}(?:\d{3})?$/', new CampoUnique('employers', 'document', $this->user->id, true)],
        ]);

        $response = Http::withHeaders([
            'X-CSRF-TOKEN' => csrf_token(),
        ])->asForm()->post(route('consultacliente'), [
            'document' => $this->user->document,
            'searchbd' => false,
        ]);

        if ($response->ok()) {
            $cliente = json_decode($response->body());
            if (isset($cliente->success) && $cliente->success) {
                $this->user->name = $cliente->name;
                if ($cliente->birthday) {
                    $this->dispatchBrowserEvent('birthday', $cliente->name);
                }
            } else {
                $this->user->refresh();
                $this->addError('user.document', $cliente->error);
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

    public function sincronizeclient()
    {
        if (empty($this->user->client)) {
            $cliente = Client::whereNull('user_id')->where('document', $this->user->document)->first();
            if ($cliente) {
                $cliente->user_id = $this->user->id;
                $cliente->save();
                $this->user->refresh();
                $this->resetValidation();
                $this->reset(['client']);
                $this->dispatchBrowserEvent('updated');
            }
        }
    }
}
