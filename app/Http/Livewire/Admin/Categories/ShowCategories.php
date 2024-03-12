<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Helpers\FormatoPersonalizado;
use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCategories extends Component
{

    use AuthorizesRequests;
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

        $categories = Category::with('subcategories')->orderBy('order', 'asc')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.categories.show-categories', compact('categories'));
    }

    public function edit(Category $category)
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->category = $category;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->category->name = trim($this->category->name);
        $this->validate();
        $this->category->save();
        $this->reset(['open']);
    }


    public function delete(Category $category)
    {

        $this->authorize('admin.almacen.categorias.delete');
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
}
