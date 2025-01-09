<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Almacen;
use App\Models\Almacenarea;
use App\Models\Category;
use App\Models\Estante;
use App\Models\Kardex;
use App\Models\Marca;
use App\Models\Pricetype;
use App\Models\Producto;
use App\Models\Unit;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShowProducto extends Component
{

    use AuthorizesRequests;

    public $producto, $almacen;
    public $isUploading = false;
    public $open = false;
    public $almacens = [];
    public $almacen_id;
    public $newcantidad = 0;
    public $subcategories = [];
    public $empresa;
    public $skuold;
    public $descripcion;

    protected function rules()
    {
        return [
            'producto.name' => ['required', 'string', 'min:3', new CampoUnique('productos', 'name', $this->producto->id, true)],
            'producto.marca_id' => ['required', 'integer', 'min:1', 'exists:marcas,id'],
            'producto.modelo' => ['nullable', 'string'],
            'producto.sku' => ['nullable', 'string', 'min:6', new CampoUnique('productos', 'sku', $this->producto->id, true)],
            'producto.partnumber' => ['nullable', 'string', 'min:4', new CampoUnique('productos', 'partnumber', $this->producto->id, true)],
            'producto.unit_id' => ['required', 'integer', 'min:1', 'exists:units,id'],
            'producto.pricebuy' => ['required', 'numeric', 'decimal:0,4', 'min:0', 'gt:0'],
            'producto.pricesale' => $this->empresa->usarLista() ? ['nullable', 'numeric', 'min:0', 'decimal:0,4'] : ['required', 'numeric', 'decimal:0,4', 'gt:0'],
            'producto.igv' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'producto.minstock' => ['required', 'integer', 'min:0'],
            'producto.category_id' => ['required', 'integer', 'min:1', 'exists:categories,id'],
            'producto.subcategory_id' => ['required', 'integer', 'min:1', 'exists:subcategories,id'],
            'producto.almacenarea_id' => ['nullable', 'integer', 'min:1', 'exists:almacenareas,id'],
            'producto.estante_id' => ['nullable', 'integer', 'min:1', 'exists:estantes,id'],
            'producto.publicado' => ['nullable', 'integer', 'min:0', 'max:1'],
            'producto.maxstockweb' => ['nullable', 'integer', 'min:1', 'max:' . $this->producto->almacens->sum('pivot.cantidad')],
            'producto.requireserie' => ['nullable', 'integer', 'min:0', 'max:1'],
            'producto.viewespecificaciones' => ['integer', 'min:0', 'max:1'],
            'producto.novedad' => ['integer', 'min:0', 'max:1'],
            'producto.viewdetalle' => ['integer', 'min:0', 'max:1'],

            'producto.precio_1' => $this->empresa->usarLista() ? ['required', 'numeric', 'decimal:0,4', 'gt:0'] : ['nullable'],
            'producto.precio_2' => $this->empresa->usarLista() ? ['required', 'numeric', 'decimal:0,4', 'gt:0'] : ['nullable'],
            'producto.precio_3' => $this->empresa->usarLista() ? ['required', 'numeric', 'decimal:0,4', 'gt:0'] : ['nullable'],
            'producto.precio_4' => $this->empresa->usarLista() ? ['required', 'numeric', 'decimal:0,4', 'gt:0'] : ['nullable'],
            'producto.precio_5' => $this->empresa->usarLista() ? ['required', 'numeric', 'decimal:0,4', 'gt:0'] : ['nullable'],
            'producto.comentario' => ['nullable', 'string'],
            'descripcion' => ['nullable', 'string']
        ];
    }

    public function mount(Producto $producto)
    {
        $this->empresa = view()->shared('empresa');
        $this->producto = $producto;
        $this->almacen = new Almacen();
        $this->subcategories = $this->producto->category->subcategories;
        // $this->almacens = Almacen::whereNotIn('id', $this->producto->almacens->pluck('id'))
        //     ->orderBy('name', 'asc')->get();
        if ($producto->marca->trashed()) {
            $this->producto->marca_id = null;
        }

        if ($producto->category->trashed()) {
            $this->producto->category_id = null;
            $this->producto->subcategory_id = null;
        }

        $this->skuold =  $this->producto->sku;
        if (empty($this->skuold)) {
            $this->producto->sku =  Self::generatesku();
        }

        if (Module::isEnabled('Marketplace')) {
            if ($this->producto->detalleproducto) {
                $this->descripcion = $this->producto->detalleproducto->descripcion;
            }
        }
    }

    public function render()
    {
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $marcas = Marca::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::activos()->orderBy('id', 'asc')->orderBy('name', 'asc')->get();

        if (Module::isEnabled('Almacen')) {
            $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
            $estantes = Estante::orderBy('name', 'asc')->get();
        } else {
            $almacenareas = [];
            $estantes = [];
        }

        return view('livewire.modules.almacen.productos.show-producto', compact('units', 'categories', 'marcas', 'almacenareas', 'estantes', 'pricetypes'));
    }

    public function generatesku()
    {
        $sku = str_pad((int)$this->producto->id, 6, '0', STR_PAD_LEFT);
        $existsku = DB::table('productos')->where('sku', $sku)->whereNot('id', $this->producto->id)->exists();
        if ($existsku) {
            $sku = DB::table('productos')->max('id');
            do {
                $sku = str_pad((int)$sku + 1, 6, '0', STR_PAD_LEFT);
            } while (DB::table('productos')->where('sku', $sku)->exists());
        }

        return $sku;
    }

    public function updatedProductoViewespecificaciones($value)
    {
        $this->authorize('admin.almacen.productos.edit');
        $this->producto->viewespecificaciones = $value ? $value : 0;
        $this->producto->save();
    }

    public function setCategory($value)
    {
        $this->reset(['subcategories']);
        $this->producto->subcategory_id = null;

        if ($value) {
            $category = Category::with('subcategories')->find($value);
            $this->subcategories = $category->subcategories;
        }
    }

    public function update($descripcion = '')
    {

        $this->authorize('admin.almacen.productos.edit');
        $this->descripcion = $descripcion;
        // dd($this->descripcion);
        $this->producto->novedad = $this->producto->novedad == false ? 0 : 1;
        $this->producto->maxstockweb = empty($this->producto->maxstockweb) ? null : $this->producto->maxstockweb;
        $this->producto->viewespecificaciones = $this->producto->viewespecificaciones == false ? 0 : 1;
        $this->producto->subcategory_id = !empty(trim($this->producto->subcategory_id)) ? trim($this->producto->subcategory_id) : null;
        $this->producto->almacenarea_id = !empty(trim($this->producto->almacenarea_id)) ? trim($this->producto->almacenarea_id) : null;
        $this->producto->estante_id = !empty(trim($this->producto->estante_id)) ? trim($this->producto->estante_id) : null;
        if ($this->empresa->autogenerateSku()) {
            $this->producto->sku = Self::generatesku();
        }
        $this->validate();
        $isDirty = $this->producto->isDirty('pricebuy') ? true : false;
        $reload = $this->producto->isDirty('name') ? true : false;
        $this->producto->save();

        if (Module::isEnabled('Marketplace')) {
            $this->producto->detalleproducto()->updateOrCreate(
                ['id' => $this->producto->detalleproducto->id ?? null],
                ['descripcion' => $this->descripcion]
            );
        }

        if ($isDirty) {
            $this->producto->assignPrice();
        }
        $this->resetValidation();
        $this->dispatchBrowserEvent('updated');
        $this->producto->refresh();

        if ($reload) {
            return route('admin.almacen.productos.edit', $this->producto);
            // return redirect()->route('admin.almacen.productos.edit', $this->producto);
            // $this->dispatchBrowserEvent('update-url', $this->producto->slug);
        }
    }

    public function openmodal()
    {
        $this->authorize('admin.almacen.productos.almacen');
        $this->almacen = new Almacen();
        $this->almacens = Almacen::whereNotIn('id', $this->producto->almacens->pluck('id'))
            ->orderBy('name', 'asc')->get();
        $this->resetValidation();
        $this->reset(['newcantidad', 'almacen_id']);
        $this->open = true;
    }

    public function savealmacen()
    {
        $this->authorize('admin.almacen.productos.almacen');
        $this->validate([
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'almacen_id' => [
                'required',
                'integer',
                'min:1',
                'exists:almacens,id',
                Rule::unique('almacen_producto', 'almacen_id')
                    ->where(fn(Builder $query) => $query
                        ->where('producto_id', $this->producto->id))
                    ->ignore($this->almacen_id, 'almacen_id')
            ],
            'newcantidad' => ['required', 'numeric', 'integer', 'min:0']
        ]);

        $event = 'created';

        DB::beginTransaction();
        try {

            if ($this->almacen->id ?? null) {
                $event = 'updated';
                $countSeries = $this->producto->seriesdisponibles
                    ->where('almacen_id', $this->almacen_id)->count();

                if ($this->newcantidad >= $countSeries) {
                    $this->producto->almacens()->updateExistingPivot($this->almacen_id, [
                        'cantidad' => $this->newcantidad,
                    ]);

                    $stock = $this->producto->almacens->find($this->almacen->id)->pivot->cantidad;
                    if ($this->newcantidad <> $stock) {
                        // dd($stock, $this->newcantidad);
                        if ($this->newcantidad > $stock) {
                            $this->producto->saveKardex($this->producto->id, $this->almacen->id, $stock, $this->newcantidad, $this->newcantidad - $stock, '+', Kardex::ACTUALIZACION_MANUAL, null);
                        } else {
                            $this->producto->saveKardex($this->producto->id, $this->almacen->id, $stock, $this->newcantidad, $stock - $this->newcantidad, '-', Kardex::ACTUALIZACION_MANUAL, null);
                        }
                    }
                } else {
                    $this->addError('newcantidad', "Cantidad es menor a las series disonibles ($countSeries).");
                    return false;
                }
            } else {

                // $almacen = Almacen::find($this->almacen_id);
                $this->producto->almacens()->attach($this->almacen_id, [
                    'cantidad' => $this->newcantidad,
                ]);
                $this->producto->saveKardex($this->producto->id, $this->almacen_id, 0, $this->newcantidad, $this->newcantidad, '+', Kardex::ENTRADA_PRODUCTO, null);
            }

            DB::commit();
            $this->reset(['newcantidad', 'almacen_id', 'open']);
            $this->resetValidation();
            $this->producto->refresh();
            $this->dispatchBrowserEvent('resetfilter');
            $this->dispatchBrowserEvent($event);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function editalmacen(Almacen $almacen)
    {
        $this->authorize('admin.almacen.productos.almacen');
        $this->almacen = $almacen;
        $this->almacen_id = $almacen->id;
        $this->newcantidad = decimalOrInteger($this->producto->almacens()->find($almacen->id)->pivot->cantidad);
        $this->resetValidation();
        $this->reset(['almacens']);
        $this->open = true;
    }

    public function deletealmacen(Almacen $almacen)
    {
        $this->authorize('admin.almacen.productos.almacen');
        try {
            DB::beginTransaction();
            $this->producto->almacens()->detach($almacen->id);
            DB::commit();
            $this->producto->refresh();
            $this->dispatchBrowserEvent('resetfilter');
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete()
    {
        $this->authorize('admin.almacen.productos.delete');
        // $ofertasdisponibles = $this->producto->promocions()->availables()->disponibles()->count();
        $tvitems = $this->producto->tvitems()->count();
        $compraitems = $this->producto->compraitems()->count();
        $cadena = extraerMensaje([
            'Items_Venta' => $tvitems,
            'Items_Compra' => $compraitems,
        ]);

        if ($tvitems > 0 || $compraitems > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar registro, ' . $this->producto->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {

            DB::beginTransaction();
            try {

                $images = $this->producto->images;
                // $this->producto->pricetypes()->delete();
                $this->producto->kardexes()->delete();
                $this->producto->images()->delete();
                $this->producto->carshoops()->delete();
                // $this->producto->almacens()->detach();
                $this->producto->series()->forceDelete();
                $this->producto->promocions()->forceDelete();
                $this->producto->forceDelete();
                DB::commit();
                if (count($images) > 0) {
                    foreach ($images as $image) {
                        if (Storage::exists('productos/' . $image->url)) {
                            Storage::delete('productos/' . $image->url);
                        }
                    }
                }
                $this->dispatchBrowserEvent('deleted');
                return redirect()->route('admin.almacen.productos');
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
