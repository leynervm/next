<?php

namespace App\Http\Livewire\Admin\Concepts;

use App\Models\Concept;
use App\Rules\CampoUnique;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ShowConcepts extends Component
{

    use WithPagination;

    public $open = false;
    public $concept;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'concept.name' => ['required', 'min:3', 'max:100', new CampoUnique('concepts', 'name', $this->concept->id, true)],
            'concept.default' => [
                'required', 'integer', 'min:0', 'max:5',
                $this->concept->default != 0 ? Rule::unique('concepts', 'default')->ignore($this->concept->id) : '',
            ]
        ];
    }

    public function mount()
    {
        $this->concept = new Concept();
    }

    public function render()
    {
        $concepts = Concept::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.concepts.show-concepts', compact('concepts'));
    }

    public function edit(Concept $concept)
    {
        $this->concept = $concept;
        $this->resetValidation();
        $this->open = true;
    }

    public function update()
    {
        $this->concept->name = trim($this->concept->name);
        $this->validate();
        $this->concept->save();
        $this->reset();
    }

    public function confirmDelete(Concept $concept)
    {
        $this->dispatchBrowserEvent('concepts.confirmDelete', $concept);
    }

    public function delete(Concept $concept)
    {
        $concept->deleteOrFail();
        $this->dispatchBrowserEvent('deleted');
    }
}
