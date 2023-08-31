<?php

namespace App\Http\Livewire\Admin\Pricetypes;

use App\Models\Pricetype;
use App\Models\Rango;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePricetype extends Component
{

    public $open = false;

    public $name;
    public $ganancia = 0;
    public $decimalrounded = 2;
    public $default = 0;
    public $web = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('pricetypes', 'name', null, true),
            ],
            'ganancia' => [
                'required', 'min:0', 'decimal:0,2'
            ],
            'decimalrounded' => [
                'required', 'integer', 'min:0', 'max:6',
            ],
            'web' => [
                'required', 'integer', 'min:0', 'max:1', new DefaultValue('pricetypes', 'web', null, true)
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:1', new DefaultValue('pricetypes', 'default', null, true)
            ]

        ];
    }

    public function render()
    {
        return view('livewire.admin.pricetypes.create-pricetype');
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
        $this->web = $this->web == 1 ? 1 : 0;
        $this->default = $this->default == 1 ? 1 : 0;
        $this->validate();

        try {
            DB::beginTransaction();
            $pricetype = Pricetype::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($pricetype) {
                $pricetype->ganancia = $this->ganancia;
                $pricetype->decimalrounded = $this->decimalrounded;
                $pricetype->web = $this->web;
                $pricetype->default = $this->default;
                $pricetype->restore();
            } else {
                $pricetype = Pricetype::create([
                    'name' => $this->name,
                    'ganancia' => $this->ganancia,
                    'decimalrounded' => $this->decimalrounded,
                    'web' => $this->web,
                    'default' => $this->default,
                ]);
            }

            $rangos = Rango::pluck('id')->toArray();
            $pricetype->rangos()->syncWithPivotValues(
                $rangos,
                [
                    'ganancia' => 0,
                    'user_id' => Auth::user()->id,
                    'created_at' => now('America/Lima'),
                    'updated_at' => now('America/Lima')
                ]
            );

            DB::commit();
            $this->emitTo('admin.pricetypes.show-pricetypes', 'render');
            $this->emitTo('admin.rangos.show-rangos', 'render');
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
