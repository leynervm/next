<?php

namespace App\Http\Livewire\Admin\Concepts;

use App\Enums\MovimientosEnum;
use App\Models\Concept;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class CreateConcept extends Component
{

    use AuthorizesRequests;

    public $open = false;

    public $name, $typemovement;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('concepts', 'name', null, true)
            ],
            'typemovement' => [
                'required', 'string', new Enum(MovimientosEnum::class),
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.concepts.create-concept');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.cajas.conceptos.create');
            $this->resetValidation();
            $this->reset();
        }
    }

    public function save($closeModal = false)
    {
        $this->authorize('admin.cajas.conceptos.create');
        $this->name = trim($this->name);
        $this->validate();

        try {
            DB::beginTransaction();
            $concept = Concept::withTrashed()
                ->whereRaw('UPPER(name) = ?', [mb_strtoupper(trim($this->name), "UTF-8")])->first();

            if ($concept) {
                $concept->typemovement = $this->typemovement;
                $concept->restore();
            } else {
                $concept = Concept::create([
                    'name' => $this->name,
                    'typemovement' => $this->typemovement,
                ]);
            }
            DB::commit();
            $this->emitTo('admin.concepts.show-concepts', 'render');

            if ($closeModal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
