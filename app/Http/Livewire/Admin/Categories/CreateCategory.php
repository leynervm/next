<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{

    use AuthorizesRequests, WithFileUploads;

    public $open = false;
    public $name, $icon;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:1',
                'max:100',
                new CampoUnique('categories', 'name', null, true),
            ],
            'icon' => [
                'nullable',
                'string',
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
            $this->reset('name', 'open', 'icon');
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.almacen.categorias.create');
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->validate();
        DB::beginTransaction();
        try {
            $orden = Category::max('orden') ?? 0;
            $category = Category::withTrashed()
                ->where('name', $this->name)->first();

            if ($category) {
                $category->orden = $orden + 1;
                $category->icon = $this->icon;
                $category->restore();
            } else {
                $category = Category::create([
                    'name' => $this->name,
                    'icon' => $this->icon,
                    'orden' => $orden + 1
                ]);
            }

            DB::commit();
            $this->emitTo('admin.categories.show-categories', 'render');
            $this->resetValidation();
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
