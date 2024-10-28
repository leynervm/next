<?php

namespace App\Http\Livewire\Admin\Pricetypes;

use App\Models\Pricetype;
use App\Rules\CampoUnique;
use App\Rules\DefaultValue;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ShowPricetypes extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public $open = false;
    public $pricetype;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'pricetype.name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('pricetypes', 'name', $this->pricetype->id)
            ],
            'pricetype.rounded' => ['required', 'integer', 'min:0', 'max:2'],
            'pricetype.decimals' => [
                'required', 'integer', 'min:0', 'max:4',
            ],
            // 'pricetype.web' => [
            //     'required', 'integer', 'min:0', 'max:1',
            //     new DefaultValue('pricetypes', 'web',  $this->pricetype->id)
            // ],
            'pricetype.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('pricetypes', 'default', $this->pricetype->id)
            ],
            'pricetype.defaultlogin' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('pricetypes', 'defaultlogin', $this->pricetype->id)
            ],
            'pricetype.temporal' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('pricetypes', 'temporal', $this->pricetype->id)
            ],
            'pricetype.startdate' => [
                'nullable', Rule::requiredIf($this->pricetype->isTemporal()),
                'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d')
            ],
            'pricetype.expiredate' => [
                'nullable', Rule::requiredIf($this->pricetype->isTemporal()),
                'date', 'after_or_equal:' . now('America/Lima')->format('Y-m-d'),
                'after_or_equal:pricetype.startdate'
            ]
        ];
    }

    public function mount()
    {
        $this->pricetype = new Pricetype();
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('id', 'asc')->paginate();
        return view('livewire.admin.pricetypes.show-pricetypes', compact('pricetypes'));
    }

    public function edit(Pricetype $pricetype)
    {
        $this->resetValidation();
        $this->pricetype = $pricetype;
        $this->open = true;
    }

    public function update()
    {

        $this->authorize('admin.administracion.pricetypes.edit');
        $this->pricetype->web = $this->pricetype->web == 1 ? 1 : 0;
        $this->pricetype->default = $this->pricetype->default == 1 ? 1 : 0;
        $this->pricetype->name = trim($this->pricetype->name);
        $this->pricetype->defaultlogin = $this->pricetype->defaultlogin == 1 ? 1 : 0;

        $this->pricetype->startdate = $this->pricetype->temporal  == true ? $this->pricetype->startdate : null;
        $this->pricetype->expiredate = $this->pricetype->temporal == true ? $this->pricetype->expiredate : null;
        $this->pricetype->temporal = $this->pricetype->temporal == true ? 1 : 0;
        $this->validate();

        try {
            DB::beginTransaction();
            $this->pricetype->save();
            DB::commit();
            $this->resetExcept(['pricetype']);
            $this->emitTo('admin.rangos.show-rangos', 'render');
            $this->dispatchBrowserEvent('updated');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function togglestatus(Pricetype $pricetype)
    {

        $this->authorize('admin.administracion.pricetypes.delete');
        try {
            DB::beginTransaction();
            $pricetype->status = $pricetype->isActivo() ? Pricetype::DISABLED : Pricetype::ACTIVO;
            $pricetype->save();
            DB::commit();
            $this->dispatchBrowserEvent('updated');
            $this->emitTo('admin.rangos.show-rangos', 'render');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
