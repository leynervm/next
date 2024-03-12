<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateCategory extends Component
{

    use AuthorizesRequests;
    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('categories', 'name', null, true),
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.categories.create-category');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.categorias.create');
            $this->resetValidation();
            $this->reset('name', 'open');
        }
    }

    public function save()
    {
        $this->authorize('admin.almacen.categorias.create');
        $this->name = trim($this->name);
        $this->validate();
        DB::beginTransaction();
        try {
            $order = Category::max('order') ?? 0;
            $category = Category::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($category) {
                $category->order = $order + 1;
                $category->restore();
            } else {
                Category::create([
                    'name' => $this->name,
                    'order' => $order + 1
                ]);
            }

            // $category->subcategories()->syncWithPivotValues($this->selectedSubcategories, [
            //     'created_at' => now('America/Lima')
            // ]);

            DB::commit();
            $this->resetValidation();
            $this->reset();
            $this->emitTo('admin.categories.show-categories', 'render');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
