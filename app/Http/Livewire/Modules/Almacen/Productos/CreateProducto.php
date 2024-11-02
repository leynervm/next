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
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;
use Nwidart\Modules\Facades\Module;

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
    public $viewdetalle = 0;
    public $viewespecificaciones = 0;
    public $requireserie = 0;
    public $minstock = 0;
    public $name, $marca_id, $modelo, $partnumber, $sku, $unit_id, $category_id,
        $subcategory_id, $almacenarea_id, $estante_id;

    public $descripcionproducto = '';

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', new CampoUnique('productos', 'name', null, true)],
            'modelo' => ['required', 'string'],
            'sku' => ['nullable', 'string', 'min:6', new CampoUnique('productos', 'sku', null, true)],
            'partnumber' => ['nullable', 'string', 'min:4', new CampoUnique('productos', 'partnumber', null, true)],
            'pricebuy' => ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'pricesale' => mi_empresa()->usarLista() ? ['nullable', 'numeric', 'min:0', 'decimal:0,4'] : ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'igv' => ['required', 'numeric', 'min:0', 'decimal:0,4'],
            'minstock' => ['required', 'integer', 'min:0'],
            'marca_id' => ['required', 'integer', 'min:1', 'exists:marcas,id'],
            'unit_id' => ['required', 'integer', 'min:1', 'exists:units,id'],
            'category_id' => ['required', 'integer', 'min:1', 'exists:categories,id'],
            'subcategory_id' => ['required', 'integer', 'min:1', 'exists:subcategories,id'],
            'selectedAlmacens' => ['nullable', 'array', 'exists:almacens,id'],
            'almacenarea_id' => ['nullable', 'integer', 'min:1', 'exists:almacenareas,id'],
            'estante_id' => ['nullable', 'integer', 'min:1', 'exists:estantes,id'],
            'publicado' => ['nullable', 'integer', 'min:0', 'max:1'],
            'viewdetalle' => ['nullable', 'integer', 'min:0', 'max:1'],
            'viewespecificaciones' => ['nullable', 'integer', 'min:0', 'max:1'],
            'requireserie' => ['nullable', 'integer', 'min:0', 'max:1'],
            'imagen' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'dimensions:min_width=500,min_height=500'],
            'descripcionproducto' => ['nullable', 'string']
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
        $this->selectedAlmacens = Almacen::orderBy('default', 'desc')->pluck('id');
        if (Module::isEnabled('Marketplace')) {
            $this->viewdetalle = Producto::VER_DETALLES;
            $this->viewespecificaciones = Producto::VER_DETALLES;
        }
    }

    public function render()
    {
        $marcas = Marca::orderBy('name', 'asc')->get();
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('orden', 'asc')->orderBy('name', 'asc')->get();
        $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
        $estantes = Estante::orderBy('name', 'asc')->get();
        // $almacens = auth()->user()->sucursal->almacens;
        $almacens = Almacen::orderBy('default', 'desc')->get();

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

    public function save($confirmsave = false)
    {
        $this->authorize('admin.almacen.productos.create');
        $this->publicado = trim($this->publicado) == 1 ? 1 : 0;
        $this->viewdetalle = trim($this->viewdetalle) == 1 ? 1 : 0;
        $this->viewespecificaciones = trim($this->viewespecificaciones) == 1 ? 1 : 0;
        $this->requireserie = trim($this->requireserie) == 1 ? 1 : 0;
        $this->name = trim(mb_strtoupper($this->name, "UTF-8"));
        $this->modelo = trim(mb_strtoupper($this->modelo, "UTF-8"));
        $this->partnumber = trim($this->partnumber);
        $this->sku = trim(mb_strtoupper($this->sku, "UTF-8"));;
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

            $exists = Producto::where('modelo', $this->modelo)->where('marca_id', $this->marca_id)->exists();
            if (!$confirmsave && $exists) {
                $this->dispatchBrowserEvent('confirmsave');
                return false;
            }
            $producto = Producto::create([
                'name' => $this->name,
                'modelo' => $this->modelo,
                'partnumber' => $this->partnumber,
                'sku' => $this->sku,
                'pricebuy' => $this->pricebuy,
                'pricesale' => $this->pricesale,
                'igv' => $this->igv,
                'minstock' => $this->minstock,
                'publicado' => $this->publicado,
                'viewdetalle' => $this->viewdetalle,
                'viewespecificaciones' => $this->viewespecificaciones,
                'requireserie' => $this->requireserie,
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
                if (!Storage::exists('images/productos')) {
                    Storage::makeDirectory('images/productos');
                }

                $compressedImage = Image::make($this->imagen->getRealPath())
                    ->resize(1500, 1500, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 30);

                $empresa = mi_empresa();
                if ($empresa->usarMarkagua()) {
                    $w = $empresa->widthmark  ?? 100;
                    $h = $empresa->heightmark  ?? 100;
                    $urlMark = public_path('storage/images/company/' . $empresa->markagua);
                    $margin = $empresa->alignmark !== 'center' ? 20 : 0;
                    // create a new Image instance for inserting
                    $mark = Image::make($urlMark)->resize($w, $h, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate();
                    $compressedImage->insert($mark, $empresa->alignmark, $margin, $margin);
                }

                $filename = uniqid('producto_') . '.' . $this->imagen->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/images/productos/' . $filename));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('imagen', 'La imagen excede el tamaño máximo permitido.');
                    return false;
                }

                $producto->images()->create([
                    'url' => $filename,
                    'default' => 1,
                ]);
            }

            if (Module::isEnabled('Marketplace') && !empty($this->descripcionproducto)) {
                $producto->detalleproducto()->create([
                    'descripcion' => $this->descripcionproducto
                ]);
            }

            if (mi_empresa()->usarlista()) {
                $producto->assignPrice();
            }
            // dd('FIN DEL PROCESO');
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
