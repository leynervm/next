<?php

namespace Modules\Almacen\Http\Livewire\Almacens;

use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Almacen\Entities\Almacen;

class CreateAlmacen extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('almacens', 'name', null, true),
            ]
        ];
    }

    public function render()
    {
        return view('almacen::livewire.almacens.create-almacen');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'open');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();
        
        DB::beginTransaction();
        try {

            $almacen = Almacen::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))
                ->first();

            if ($almacen) {
                $almacen->restore();
            } else {
                Almacen::create([
                    'name' => $this->name
                ]);
            }

            DB::commit();
            $this->emitTo('almacen::almacens.show-almacens', 'render');
            $this->reset('name', 'open');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
