<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Almacen;
use App\Models\Category;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Unit;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Almacenarea;
use App\Models\Estante;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\ImageManagerStatic as Image;

class CreateProducto extends Component
{
    use WithFileUploads, AuthorizesRequests;

    public $imagen;
    public $identificador;
    public $subcategories = [];
    public $selectedAlmacens = [];

    public $pricebuy = 0;
    public $pricesale = 0;
    public $igv = 0;
    public $publicado = 0;
    public $minstock = 0;
    public $name, $marca_id, $modelo, $codefabricante, $unit_id, $category_id,
        $subcategory_id, $almacenarea_id, $estante_id;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:3', new CampoUnique('productos', 'name', null, true)
            ],
            'marca_id' => ['required', 'integer', 'min:1', 'exists:marcas,id'],
            'modelo' => ['required', 'string'],
            'codefabricante' => ['nullable', 'string', 'min:4'],
            'pricebuy' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'pricesale' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'igv' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'minstock' => ['required', 'integer', 'min:0'],
            'unit_id' => ['required', 'integer', 'min:1', 'exists:units,id'],
            'category_id' => ['required', 'integer', 'min:1', 'exists:categories,id'],
            'subcategory_id' => ['nullable', 'integer', 'min:1', 'exists:subcategories,id'],
            'selectedAlmacens' => ['required', 'array', 'min:1', 'exists:almacens,id'],
            'almacenarea_id' => ['nullable', 'integer', 'min:1', 'exists:almacenareas,id'],
            'estante_id' => ['nullable', 'integer', 'min:1', 'exists:estantes,id'],
            'publicado' => ['nullable', 'integer', 'min:0', 'max:1'],
            'imagen' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
    }

    public function render()
    {
        $marcas = Marca::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('orden', 'asc')->orderBy('name', 'asc')->get();
        $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
        $estantes = Estante::orderBy('name', 'asc')->get();
        $almacens = auth()->user()->sucursal->almacens;

        return view('livewire.modules.almacen.productos.create-producto', compact('marcas', 'units', 'categories', 'almacenareas', 'estantes', 'almacens'));
    }

    public function updatedCategoryId($value)
    {
        $this->reset(['subcategory_id', 'subcategories']);

        if ($value) {
            $category = Category::with('subcategories')->find($value);
            $this->subcategories = $category->subcategories()
                ->orderBy('orden', 'asc')->orderBy('name', 'asc')->get();
        }
    }

    public function save()
    {
        $this->authorize('admin.almacen.productos.create');
        $this->name = trim($this->name);
        $this->modelo = trim($this->modelo);
        $this->codefabricante = trim($this->codefabricante);
        $this->pricebuy = trim($this->pricebuy);
        $this->pricesale = trim($this->pricesale);
        $this->igv = trim($this->igv);
        $this->marca_id = trim($this->marca_id);
        $this->unit_id = trim($this->unit_id);
        $this->category_id = trim($this->category_id);
        $this->subcategory_id = !empty(trim($this->subcategory_id)) ? trim($this->subcategory_id) : null;
        $this->almacenarea_id = !empty(trim($this->almacenarea_id)) ? trim($this->almacenarea_id) : null;
        $this->estante_id = !empty(trim($this->estante_id)) ? trim($this->estante_id) : null;
        $this->validate();

        DB::beginTransaction();
        try {
            $producto = Producto::create([
                'name' => $this->name,
                'modelo' => $this->modelo,
                'codefabricante' => $this->codefabricante,
                'pricebuy' => $this->pricebuy,
                'pricesale' => $this->pricesale,
                'igv' => $this->igv,
                'minstock' => $this->minstock,
                'publicado' => $this->publicado,
                'code' => Str::random(9),
                'almacenarea_id' => $this->almacenarea_id,
                'estante_id' => $this->estante_id,
                'marca_id' => $this->marca_id,
                'category_id' => $this->category_id,
                'subcategory_id' => $this->subcategory_id,
                'unit_id' => $this->unit_id,
                'user_id' => auth()->user()->id,
            ]);

            $producto->almacens()->syncWithPivotValues($this->selectedAlmacens, [
                'cantidad' => 0,
            ]);

            if ($this->imagen) {
                if (!Storage::exists('productos')) {
                    Storage::makeDirectory('productos');
                }

                $compressedImage = Image::make($this->imagen->getRealPath())
                    ->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->orientate()
                    ->encode('jpg', 30);

                $filename = uniqid('producto_') . '.' . $this->imagen->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/productos/' . $filename));

                if ($compressedImage->filesize() > 1048576) {
                    //1MB
                    $compressedImage->destroy();
                    $compressedImage->delete();
                    $this->addError('imagen', 'La imagen excede el tamaño máximo permitido.');
                }

                $producto->images()->create([
                    'url' => $filename,
                    'default' => 1,
                ]);
            }

            DB::commit();
            $this->resetValidation();
            $this->reset();
            $this->dispatchBrowserEvent('created');
            if (auth()->user()->hasPermissionTo('admin.almacen.productos.edit')) {
                return redirect()->route('admin.almacen.productos.edit', ['producto' => $producto]);
            } else {
                return redirect()->route('admin.almacen.productos');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function clearImage()
    {
        $this->reset(['imagen']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function updatedImagen($file)
    {
        try {
            $url = $file->temporaryUrl();
        } catch (\Exception $e) {
            $this->reset(['imagen']);
            $this->addError('imagen', $e->getMessage());
            return;
        }
    }
}
