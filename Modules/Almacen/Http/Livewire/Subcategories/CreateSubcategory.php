<?php

namespace Modules\Almacen\Http\Livewire\Subcategories;

use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\Almacen\Entities\Subcategory;
use Illuminate\Support\Str;

class CreateSubcategory extends Component
{

    public $open = false;
    public $name;
    public $selectedCategories = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:2', 'max:100',
                new CampoUnique('subcategories', 'name', null, true),
            ],
            'selectedCategories' => [
                'required', 'array', 'min:1'
            ]
        ];
    }

    public function render()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('almacen::livewire.subcategories.create-subcategory', compact('categories'));
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('name', 'selectedCategories');
        }
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->validate();

        DB::beginTransaction();

        try {
            $subcategory = Subcategory::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($subcategory) {
                $subcategory->restore();
            } else {
                $subcategory = Subcategory::create([
                    'name' => $this->name,
                    'code' => Str::random(4)
                ]);
            }

            $subcategory->categories()->syncWithPivotValues($this->selectedCategories, [
                'user_id' => Auth::user()->id,
                'created_at' => now('America/Lima')
            ]);
            DB::commit();
            $this->emitTo('almacen::subcategories.show-subcategories', 'render');
            $this->reset('name', 'selectedCategories', 'open');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
