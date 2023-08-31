<?php

namespace Modules\Almacen\Http\Livewire\Categories;

use App\Models\Category;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Almacen\Entities\Subcategory;

class ShowCategories extends Component
{

    use WithPagination;

    public $open = false;
    public $category;
    public $selectedSubcategories = [];

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'category.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('categories', 'name', $this->category->id, true),
            ],
            'selectedSubcategories' => [
                'nullable'
            ]
        ];
    }

    public function mount()
    {
        $this->category = new Category();
    }

    public function render()
    {
        $categories = Category::orderBy('name', 'asc')->paginate();
        $subcategories = Subcategory::orderBy('name', 'asc')->get();
        return view('almacen::livewire.categories.show-categories', compact('categories', 'subcategories'));
    }

    public function edit(Category $category)
    {
        $this->category = $category;
        $this->selectedSubcategories = $category->subcategories()->pluck('subcategory_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->category->name = trim($this->category->name);
        $this->validate();
        $this->category->save();
        $this->category->subcategories()->syncWithPivotValues($this->selectedSubcategories, [
            'user_id' => Auth::user()->id,
            'updated_at' => now('America/Lima')
        ]);
        $this->reset(['open']);
    }

    public function confirmDelete(Category $category)
    {
        $this->dispatchBrowserEvent('categories.confirmDelete', $category);
    }

    public function delete(Category $category)
    {
        DB::beginTransaction();
        try {
            $category->subcategories()->detach();
            $category->deleteOrFail();
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
