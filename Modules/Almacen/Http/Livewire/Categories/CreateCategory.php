<?php

namespace Modules\Almacen\Http\Livewire\Categories;

use App\Models\Category;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;
use Modules\Almacen\Entities\Subcategory;

class CreateCategory extends Component
{

    public $open = false;
    public $name;
    public $selectedSubcategories = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('categories', 'name', null, true),
            ],
            'selectedSubcategories' => [
                'nullable'
            ]
        ];
    }

    public function render()
    {
        $subcategories = Subcategory::orderBy('name', 'asc')->get();
        return view('almacen::livewire.categories.create-category', compact('subcategories'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'selectedSubcategories', 'open');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        DB::beginTransaction();

        try {
            $category = Category::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($category) {
                $category->restore();
            } else {
                $category = Category::create([
                    'name' => $this->name,
                    'code' => Str::random(4)
                ]);
            }

            $category->subcategories()->syncWithPivotValues($this->selectedSubcategories, [
                'user_id' => Auth::user()->id,
                'created_at' => now('America/Lima')
            ]);

            DB::commit();
            $this->emitTo('almacen::categories.show-categories', 'render');
            $this->reset('name', 'selectedSubcategories', 'open');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
