<?php

namespace App\Http\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowSubcategories extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $open = false;
    public $subcategory;
    public $selectedCategories = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'subcategory.name' => [
                'required', 'string', 'min:2', 'max:100',
                new CampoUnique('subcategories', 'name', $this->subcategory->id, false),
            ],
            'selectedCategories' => ['required', 'array', 'min:1']
        ];
    }

    public function mount()
    {
        $this->subcategory = new Subcategory();
    }

    public function render()
    {

        $subcategories = Subcategory::orderBy('order', 'asc')->orderBy('name', 'asc')->paginate(50);
        $categories = Category::orderBy('order', 'asc')->orderBy('name', 'asc')->get();

        return view('livewire.admin.subcategories.show-subcategories', compact('subcategories', 'categories'));
    }

    public function edit(Subcategory $subcategory)
    {
        $this->authorize('admin.almacen.subcategorias.edit');
        $this->subcategory = $subcategory;
        $this->selectedCategories = $subcategory->categories()->pluck('category_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.subcategorias.edit');
        $this->subcategory->name = trim($this->subcategory->name);
        $this->validate();
        $this->subcategory->save();
        $this->subcategory->categories()->sync($this->selectedCategories);
        $this->reset(['open']);
    }

    public function delete(Subcategory $subcategory)
    {
        $this->authorize('admin.almacen.subcategorias.delete');
        DB::beginTransaction();
        try {
            $subcategory->categories()->detach();
            $subcategory->delete();
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
