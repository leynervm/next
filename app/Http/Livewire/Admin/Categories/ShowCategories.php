<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Helpers\FormatoPersonalizado;
use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCategories extends Component
{

    use WithPagination;

    public $open = false;
    public $category;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'category.name' => [
                'required', 'string', 'min:3', 'max:100', new CampoUnique('categories', 'name', $this->category->id, true),
            ],
        ];
    }

    public function mount()
    {
        $this->category = new Category();
    }

    public function render()
    {

        $categories = Category::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.categories.show-categories', compact('categories'));
    }

    public function edit(Category $category)
    {
        $this->category = $category;
        $this->open = true;
    }

    public function update()
    {
        $this->category->name = trim($this->category->name);
        $this->validate();
        $this->category->save();
        // $this->category->subcategories()->syncWithPivotValues($this->selectedSubcategories, [
        //     'updated_at' => now('America/Lima')
        // ]);
        $this->reset(['open']);
    }


    public function delete(Category $category)
    {

        $productos = $category->productos()->count();
        $cadena = FormatoPersonalizado::extraerMensaje([
            'Productos' => $productos
        ]);

        if ($productos > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar categoría, ' . $category->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {
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
}
