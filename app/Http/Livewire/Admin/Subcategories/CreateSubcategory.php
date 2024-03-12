<?php

namespace App\Http\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateSubcategory extends Component
{

    use AuthorizesRequests;
    public $open = false;
    public $name;
    public $selectedCategories = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:2', 'max:100',
                new CampoUnique('subcategories', 'name', null, false),
            ],
            'selectedCategories' => ['required', 'array', 'min:1']
        ];
    }


    public function render()
    {
        $categories = Category::orderBy('order', 'asc')->orderBy('name', 'asc')->get();
        return view('livewire.admin.subcategories.create-subcategory', compact('categories'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.subcategorias.create');
            $this->resetValidation();
            $this->reset('name', 'selectedCategories');
        }
    }

    public function save()
    {
        $this->authorize('admin.almacen.subcategorias.create');
        $this->name = trim($this->name);
        $this->validate();
        DB::beginTransaction();
        try {
            $order = Subcategory::max('order') ?? 0;
            $subcategory = Subcategory::create([
                'name' => $this->name,
                'order' => $order + 1
            ]);
            $subcategory->categories()->sync($this->selectedCategories);
            DB::commit();
            $this->reset();
            $this->resetValidation();
            $this->emitTo('admin.subcategories.show-subcategories', 'render');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
