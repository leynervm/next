<?php

namespace App\Http\Livewire\Admin\Subcategories;

use App\Helpers\FormatoPersonalizado;
use App\Models\Category;
use App\Models\Subcategory;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
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
                'required', 'string', 'min:3', 'max:100',
                new CampoUnique('subcategories', 'name', $this->subcategory->id, true),
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

        $subcategories = Subcategory::orderBy('name', 'asc')->paginate(50);
        $categories = Category::orderBy('name', 'asc')->get();

        return view('livewire.admin.subcategories.show-subcategories', compact('subcategories', 'categories'));
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
        $this->subcategory->categories()->sync($this->selectedCategories);
        $this->reset(['open']);
    }

    public function delete(Subcategory $subcategory)
    {
        $productos = $subcategory->productos()->count();
        $cadena = FormatoPersonalizado::extraerMensaje([
            'Productos' => $productos
        ]);

        if ($productos > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar subcategoría, ' . $subcategory->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
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
}
