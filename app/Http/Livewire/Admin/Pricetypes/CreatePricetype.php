<?php

namespace App\Http\Livewire\Admin\Pricetypes;

use App\Models\Pricetype;
use App\Models\Rango;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;

class CreatePricetype extends Component
{

    public $open = false;
    public $show = false;

    public $name;
    public $decimals = '2';
    public $rounded = 0;
    public $decimalrounded = 0;
    public $roundedinteger = 0;
    public $default = 0;
    public $temporal = 0;
    public $defaultlogin = 0;
    public $web = 0;
    public $startdate = '';
    public $expiredate = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:100', new CampoUnique('pricetypes', 'name', null, true)],
            'rounded' => ['required', 'integer', 'min:0', 'max:2'],
            'decimals' => ['required', 'integer', 'min:0', 'max:4'],
            'web' => ['required', 'integer', 'min:0', 'max:1', new DefaultValue('pricetypes', 'web', null, true)],
            'defaultlogin' => ['required', 'integer', 'min:0', 'max:1', new DefaultValue('pricetypes', 'defaultlogin', null, true)],
            'temporal' => ['required', 'integer', 'min:0', 'max:1', new DefaultValue('pricetypes', 'temporal', null, true)],
            'startdate' => [
                'nullable', Rule::requiredIf($this->temporal == 1),
                'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d')
            ],
            'expiredate' => [
                'nullable', Rule::requiredIf($this->temporal == 1),
                'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d'),
                'after_or_equal:startdate'
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
        $this->defaultlogin = $this->defaultlogin == 1 ? 1 : 0;
        $this->startdate = $this->temporal  == true ? $this->startdate : null;
        $this->expiredate = $this->temporal == true ? $this->expiredate : null;
        $this->temporal = $this->temporal == true ? 1 : 0;
        $this->validate();

        try {
            DB::beginTransaction();
            $pricetype = Pricetype::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($pricetype) {
                $pricetype->rounded = $this->rounded;
                $pricetype->decimals = $this->decimals;
                $pricetype->web = $this->web;
                $pricetype->defaultlogin = $this->defaultlogin;
                $pricetype->default = $this->default;
                $pricetype->restore();
            } else {
                $pricetype = Pricetype::create([
                    'name' => $this->name,
                    'rounded' => $this->rounded,
                    'decimals' => $this->decimals,
                    'web' => $this->web,
                    'defaultlogin' => $this->defaultlogin,
                    'default' => $this->default,
                ]);
            }

            $rangos = Rango::pluck('id')->toArray();
            $pricetype->rangos()->syncWithPivotValues($rangos, ['ganancia' => 0]);

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
