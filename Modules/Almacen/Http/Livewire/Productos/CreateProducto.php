<?php

namespace Modules\Almacen\Http\Livewire\Productos;

use App\Models\Category;
use App\Models\Marca;
use App\Models\Tribute;
use App\Models\Unit;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Almacenarea;
use Modules\Almacen\Entities\Estante;
use Modules\Almacen\Entities\Producto;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class CreateProducto extends Component
{
    use WithFileUploads;

    public $imagen;
    public $identificador;
    public $isUploading = false;

    public $tribute;

    public $subcategories = [];
    public $selectedAlmacens = [];

    public $pricebuy = 0;
    public $igv = 0;
    public $publicado = 0;
    public $name, $marca_id, $modelo, $unit_id,
        $category_id, $subcategory_id, $almacenarea_id, $estante_id;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', new CampoUnique('productos', 'name', null, true)
            ],
            'marca_id' => ['required', 'exists:marcas,id'],
            'modelo' => ['required'],
            'pricebuy' => ['required', 'decimal:0,2', 'min:0'],
            'igv' => ['required', 'decimal:0,2', 'min:0'],
            'unit_id' => ['required', 'exists:units,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'exists:subcategories,id'],
            'selectedAlmacens' => ['required', 'array', 'min:1', 'exists:almacens,id'],
            'almacenarea_id' => ['required', 'exists:almacenareas,id'],
            'estante_id' => ['required', 'exists:estantes,id'],
            'publicado' => ['nullable', 'min:0', 'max:1'],
            'tribute.id' => ['required', 'exists:tributes,id'],
            'imagen' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120']
        ];
    }

    public function mount()
    {
        $this->identificador  = rand();
        $this->tribute  = new Tribute();
    }

    public function render()
    {
        $marcas = Marca::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
        $estantes = Estante::orderBy('name', 'asc')->get();
        $almacens = Almacen::orderBy('name', 'asc')->get();

        return view('almacen::livewire.productos.create-producto', compact('marcas', 'units', 'categories', 'almacenareas', 'estantes', 'almacens'));
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-producto-select2');
    }

    public function updatedCategoryId($value)
    {

        $this->reset(['subcategory_id', 'subcategories']);

        if ($value) {
            $category = Category::find($value);
            $this->subcategories = $category->subcategories;
        }
    }

    public function save()
    {

        $this->tribute = Tribute::where('default', 1)->first();
        $this->name = trim($this->name);
        $this->modelo = trim($this->modelo);
        $this->pricebuy = trim($this->pricebuy);
        $this->igv = trim($this->igv);
        $this->marca_id = trim($this->marca_id);
        $this->unit_id = trim($this->unit_id);
        $this->category_id = trim($this->category_id);
        // $this->subcategory_id = $this->subcategory_id == "" ? null : trim($this->subcategory_id);
        $this->almacenarea_id = trim($this->almacenarea_id);
        $this->estante_id = trim($this->estante_id);
        $this->validate();

        DB::beginTransaction();

        try {

            $producto = Producto::create([
                'name' => $this->name,
                'modelo' => $this->modelo,
                'pricebuy' => $this->pricebuy,
                'igv' => $this->igv,
                'publicado' => $this->publicado,
                'sku' => Str::random(4),
                'almacenarea_id' => $this->almacenarea_id,
                'estante_id' => $this->estante_id,
                'marca_id' => $this->marca_id,
                'category_id' => $this->category_id,
                'subcategory_id' => $this->subcategory_id,
                'tribute_id' => $this->tribute->id,
                'unit_id' => $this->unit_id,
                'user_id' => Auth::user()->id
            ]);

            $producto->almacens()->syncWithPivotValues($this->selectedAlmacens, [
                'date' => now('America/Lima'),
                'created_at' => now('America/Lima'),
                'cantidad' => 0,
                'user_id' => Auth::user()->id
            ]);

            if ($this->imagen) {

                if (!Storage::exists('productos')) {
                    Storage::makeDirectory('productos');
                }

                $compressedImage = Image::make($this->imagen->getRealPath())
                    ->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 30);

                $filename = uniqid('producto_') . '.' . $this->imagen->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/productos/' . $filename));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $compressedImage->delete();
                    $this->addError('imagen', 'La imagen excede el tamaño máximo permitido.');
                }

                $producto->images()->create([
                    'url' => $filename,
                    'default' => 1
                ]);
            }

            DB::commit();
            $this->resetValidation();
            $this->reset();

            return redirect()->route('admin.almacen.productos.show', ['producto' => $producto]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
