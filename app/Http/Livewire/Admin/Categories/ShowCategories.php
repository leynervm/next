<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Helpers\FormatoPersonalizado;
use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ShowCategories extends Component
{

    use AuthorizesRequests, WithPagination;

    public $open = false;
    public $category;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'category.name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                new CampoUnique('categories', 'name', $this->category->id, true),
            ],
            'category.icon' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function mount()
    {
        $this->category = new Category();
    }

    public function render()
    {
        $categories = Category::with('subcategories')->orderBy('orden', 'asc')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.categories.show-categories', compact('categories'));
    }

    public function edit(Category $category)
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->category = $category;
        $this->open = true;
    }

    public function update($closemodal = false)
    {

        $this->authorize('admin.almacen.categorias.edit');
        $this->category->name = trim($this->category->name);
        $this->validate();
        $this->category->save();
        $this->resetValidation();
        if ($closemodal) {
            $this->reset(['open']);
        } else {
            $this->category->refresh();
        }
    }


    public function delete(Category $category)
    {

        $this->authorize('admin.almacen.categorias.delete');
        DB::beginTransaction();
        try {
            $category->subcategories()->detach();
            $category->delete();
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
