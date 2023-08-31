<?php

namespace App\Http\Livewire\Admin\Bancos;

use App\Models\Banco;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateBanco extends Component
{
    public $open = false;
    public $name;

    public function render()
    {
        return view('livewire.admin.bancos.create-banco');
    }

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('bancos', 'name', null, true)
            ],
        ];
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        try {
            DB::beginTransaction();
            $banco = Banco::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($banco) {
                $banco->restore();
            } else {
                $banco = Banco::create([
                    'name' => $this->name
                ]);
            }

            DB::commit();
            $this->emitTo('admin.bancos.show-bancos', 'render');
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
