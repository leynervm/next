<?php

namespace App\Http\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Subcategory;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateSubcategory extends Component
{

    public $open = false;
    public $name;
    public $selectedCategories = [];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('subcategories', 'name', null, true),
            ],
            'selectedCategories' => ['required', 'array', 'min:1']
        ];
    }


    public function render()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        return view('livewire.admin.subcategories.create-subcategory', compact('categories'));
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
