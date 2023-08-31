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
use Livewire\WithPagination;

class ShowPricetypes extends Component
{
    use WithPagination;

    public $open = false;
    public $pricetype;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'pricetype.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('pricetypes', 'name', $this->pricetype->id, true),
            ],
            'pricetype.ganancia' => [
                'required', 'min:0', 'decimal:0,2'
            ],
            'pricetype.decimalrounded' => [
                'required', 'integer', 'min:0', 'max:6',
            ],
            'pricetype.web' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('pricetypes', 'web',  $this->pricetype->id, true)
            ],
            'pricetype.default' => [
                'required', 'integer', 'min:0', 'max:1',
                new DefaultValue('pricetypes', 'default', $this->pricetype->id, true)
            ]

        ];
    }

    public function mount()
    {
        $this->pricetype = new Pricetype();
    }

    public function render()
    {
        $pricetypes = Pricetype::orderBy('ganancia', 'asc')
            ->orderBy('name', 'desc')->paginate();
        return view('livewire.admin.pricetypes.show-pricetypes', compact('pricetypes'));
    }

    public function edit(Pricetype $pricetype)
    {
        $this->pricetype = $pricetype;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->pricetype->web = $this->pricetype->web == 1 ? 1 : 0;
        $this->pricetype->default = $this->pricetype->default == 1 ? 1 : 0;
        $this->pricetype->name = trim($this->pricetype->name);
        $this->validate();

        try {
            DB::beginTransaction();
            $this->pricetype->save();
            $rangos = Rango::pluck('id')->toArray();
            $this->pricetype->rangos()->syncWithPivotValues(
                $rangos,
                [
                    // 'ganancia' => $this->pricetype->ganancia,
                    'user_id' => Auth::user()->id,
                    'created_at' => now('America/Lima'),
                    'updated_at' => now('America/Lima')
                ]
            );
            DB::commit();
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

    public function confirmDelete(Pricetype $pricetype)
    {
        $this->dispatchBrowserEvent('pricetypes.confirmDelete', $pricetype);
    }

    public function delete(Pricetype $pricetype)
    {
        $pricetype->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
