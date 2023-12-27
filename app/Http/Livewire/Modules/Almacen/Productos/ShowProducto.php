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
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Almacen\Entities\Almacenarea;
use Modules\Almacen\Entities\Estante;
use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Query\Builder;

class ShowProducto extends Component
{

    use WithFileUploads;

    public $producto, $almacen;
    public $isUploading = false;
    public $open = false;
    public $almacens = [];
    public $almacen_id;
    public $newcantidad = 0;
    public $subcategories = [];

    protected $listeners = ['delete', 'deletealmacen'];

    protected function rules()
    {
        return [
            'producto.name' => [
                'required', 'min:3', new CampoUnique('productos', 'name', $this->producto->id, true)
            ],
            'producto.marca_id' => ['required', 'integer', 'min:1', 'exists:marcas,id'],
            'producto.modelo' => ['required'],
            'producto.unit_id' => ['required', 'integer', 'min:1', 'exists:units,id'],
            'producto.pricebuy' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'producto.pricesale' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'producto.igv' => ['required', 'numeric', 'decimal:0,4', 'min:0'],
            'producto.category_id' => ['required', 'integer', 'min:1', 'exists:categories,id'],
            'producto.subcategory_id' => ['nullable', 'integer', 'min:1', 'exists:subcategories,id'],
            'producto.almacenarea_id' => ['required', 'integer', 'min:1', 'exists:almacenareas,id'],
            'producto.estante_id' => ['required', 'integer', 'min:1', 'exists:estantes,id'],
            'producto.publicado' => ['nullable', 'integer', 'min:0', 'max:1'],
        ];
    }

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->almacen = new Almacen();
        $this->almacens = Almacen::whereNotIn('id', $this->producto->almacens->pluck('id'))
            ->orderBy('name', 'asc')->get();
        $this->subcategories = Category::find($this->producto->category_id)->subcategories;

    }

    public function render()
    {
        $units = Unit::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $marcas = Marca::orderBy('name', 'asc')->get();

        if (Module::isEnabled('Almacen')) {
            $almacenareas = Almacenarea::orderBy('name', 'asc')->get();
            $estantes = Estante::orderBy('name', 'asc')->get();
        } else {
            $almacenareas = [];
            $estantes = [];
        }

        return view('livewire.modules.almacen.productos.show-producto', compact('units', 'categories', 'marcas', 'almacenareas', 'estantes'));
    }

    public function updatedProductoPublicado($value)
    {
        $this->producto->publicado = $value ? $value : 0;
        $this->producto->save();
        $this->dispatchBrowserEvent('updated');
    }

    public function updatedProductoCategoryId($value)
    {
        $this->reset(['subcategories']);
        if (trim($value) !== "") {
            $this->subcategories = Category::find($value)->subcategories;
        }
    }

    public function update()
    {
        $this->validate();
        $this->producto->save();
        $this->dispatchBrowserEvent('updated');
    }

    public function openmodal()
    {
        $this->almacen = new Almacen();
        $this->almacens = Almacen::whereNotIn('id', $this->producto->almacens->pluck('id'))
            ->orderBy('name', 'asc')->get();
        $this->resetValidation();
        $this->reset(['newcantidad', 'almacen_id']);
        $this->open = true;
    }

    public function savealmacen()
    {
        $this->validate([
            'producto.id' => ['required', 'exists:productos,id'],
            'almacen_id' => [
                'required', 'integer', 'min:1', 'exists:almacens,id',
                Rule::unique('almacen_producto', 'almacen_id')
                    ->where(fn(Builder $query) => $query
                        ->where('producto_id', $this->producto->id))
                    ->ignore($this->almacen_id, 'almacen_id')
            ],
            'newcantidad' => ['required', 'decimal:0,2', 'min:0']
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
                } else {
                    $this->addError('newcantidad', "Cantidad es menor a las series disonibles ($countSeries).");
                    return false;
                }
            } else {
                $this->producto->almacens()->attach($this->almacen_id, [
                    'cantidad' => $this->newcantidad,
                ]);
            }

            DB::commit();
            $this->reset(['newcantidad', 'almacen_id', 'open']);
            $this->resetValidation();
            $this->producto->refresh();
            $this->emitTo('modules.almacen.productos.show-series', 'resetfilter');
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
        $this->almacen = $almacen;
        $this->almacen_id = $almacen->id;
        $this->newcantidad = $this->producto->almacens()->find($almacen->id)->pivot->cantidad;
        $this->resetValidation();
        $this->reset(['almacens']);
        $this->open = true;
    }

    public function deletealmacen(Almacen $almacen)
    {

        $series = $this->producto->almacens()->find($almacen->id);
        $seriesout = $series->series()->salidas()->count();
        $tvitems = $this->producto->almacens()->find($almacen->id)->tvitems()->count();

        $cadena = extraerMensaje([
            'Series' => $seriesout,
            'Items_Venta' => $tvitems,
        ]);

        if ($seriesout > 0 || $tvitems > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar registro, ' . $almacen->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {

            DB::beginTransaction();
            try {

                $carshoops = $this->producto->carshoops();
                if ($carshoops->exists()) {
                    foreach ($carshoops->get() as $carshoop) {
                        $carshoop->carshoopseries()->delete();
                        $carshoop->delete();
                    }
                }

                $series->series()->forceDelete();
                $this->producto->almacens()->detach($almacen);
                DB::commit();
                $this->producto->refresh();
                $this->emitTo('modules.almacen.productos.show-series', 'resetfilter');
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

    public function delete()
    {
        $ofertasdisponibles = $this->producto->ofertasdisponibles()->count();
        $tvitems = $this->producto->tvitems()->count();
        $compraitems = $this->producto->compraitems()->count();
        $itemguias = $this->producto->compraitems()->count();
        $cadena = extraerMensaje([
            'Items_Venta' => $tvitems,
            'Items_Compra' => $compraitems,
            'Ofertas' => $ofertasdisponibles,
            'Items_Guia' => $itemguias
        ]);

        if ($tvitems > 0 || $compraitems > 0 || $ofertasdisponibles > 0) {
            $mensaje = response()->json([
                'title' => 'No se puede eliminar registro, ' . $this->producto->name,
                'text' => "Existen registros vinculados $cadena, eliminarlo causaría un conflicto en la base de datos."
            ])->getData();
            $this->dispatchBrowserEvent('validation', $mensaje);
        } else {

            DB::beginTransaction();
            try {

                $this->producto->garantiaproductos()->delete();
                $this->producto->especificaciones()->forceDelete();
                $this->producto->detalleproductos()->delete();
                $this->producto->ofertas()->forceDelete();
                $this->producto->pricetypes()->delete();
                $this->producto->ofertas()->forceDelete();

                $carshoops = $this->producto->carshoops();
                if ($carshoops->exists()) {
                    foreach ($carshoops->get() as $carshoop) {
                        $carshoop->carshoopseries()->delete();
                        $carshoop->delete();
                    }
                }

                if ($this->producto->images()->exists()) {
                    foreach ($this->producto->images as $image) {
                        if (Storage::exists('productos/' . $image->url)) {
                            Storage::delete('productos/' . $image->url);
                        }
                        $image->delete();
                    }
                }

                $this->producto->almacens()->detach();
                $this->producto->series()->forceDelete();
                $this->producto->forceDelete();
                DB::commit();
                $this->dispatchBrowserEvent('deleted');
                return redirect()->route('admin.almacen');

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
