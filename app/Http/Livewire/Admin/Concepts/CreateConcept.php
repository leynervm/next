<?php

namespace App\Http\Livewire\Admin\Concepts;

use App\Models\Concept;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateConcept extends Component
{

    public $open = false;

    public $name;
    public $default = 0;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100',
                new CampoUnique('concepts', 'name', null, true)
            ],
            'default' => [
                'required', 'integer', 'min:0', 'max:6',
                $this->default != 0 ? Rule::unique('concepts', 'default') : '',
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.concepts.create-concept');
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
            $concept = Concept::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($concept) {
                $concept->default = $this->default;
                $concept->restore();
            } else {
                $concept = Concept::create([
                    'name' => $this->name,
                    'default' => $this->default,
                ]);
            }

            DB::commit();
            $this->emitTo('admin.concepts.show-concepts', 'render');
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
