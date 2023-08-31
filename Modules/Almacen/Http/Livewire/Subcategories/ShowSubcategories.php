<?php

namespace Modules\Almacen\Http\Livewire\Subcategories;

use App\Models\Category;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Almacen\Entities\Subcategory;
use Livewire\WithPagination;

class ShowSubcategories extends Component
{

    use WithPagination;

    public $open = false;
    public $subcategory;
    public $selectedCategories = [];

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'subcategory.name' => [
                'required', 'min:2', 'max:100',
                new CampoUnique('subcategories', 'name', $this->subcategory->id, true),
            ],
            'selectedCategories' => [
                'required', 'array', 'min:1'
            ]
        ];
    }

    public function mount()
    {
        $this->subcategory = new Subcategory();
    }

    public function render()
    {
        $subcategories = Subcategory::orderBy('name', 'asc')->paginate();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('almacen::livewire.subcategories.show-subcategories', compact('subcategories', 'categories'));
    }

    public function edit(Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;
        $this->selectedCategories = $subcategory->categories()->pluck('category_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->subcategory->name = trim($this->subcategory->name);
        $this->validate();
        $this->subcategory->save();
        $this->subcategory->categories()->syncWithPivotValues($this->selectedCategories, [
            'user_id' => Auth::user()->id,
            'created_at' => now('America/Lima'),
            'updated_at' => now('America/Lima')
        ]);
        $this->reset(['open']);
    }

    public function confirmDelete(Subcategory $subcategory)
    {
        $this->dispatchBrowserEvent('subcategories.confirmDelete', $subcategory);
    }

    public function delete(Subcategory $subcategory)
    {
        DB::beginTransaction();
        try {
            $subcategory->categories()->detach();
            $subcategory->deleteOrFail();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
