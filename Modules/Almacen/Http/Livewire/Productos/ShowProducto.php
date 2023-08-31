<?php

namespace Modules\Almacen\Http\Livewire\Productos;

use App\Models\Caracteristica;
use App\Models\Especificacion;
use App\Models\Image;
use App\Models\Pricetype;
use App\Models\Serie;
use App\Rules\CampoUnique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Modules\Almacen\Entities\Producto;
use Modules\Almacen\Entities\Typegarantia;
use Intervention\Image\ImageManagerStatic as ImageIntervention;
use Livewire\WithFileUploads;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Garantiaproducto;

class ShowProducto extends Component
{

    use WithFileUploads;

    public $producto;
    public $typegarantia;

    public $isUploading = false;
    public $openalmacen = false;
    public $open = false;
    public $openespecificacion = false;

    public $identificador;
    public $imagen;

    public $almacens = [];
    public $searchseriealmacen = [];
    public $selectedEspecificacion = [];
    public $seriesalmacen = [];

    public $almacen_id, $serie;
    public $typegarantia_id, $time;
    public $titulo, $descripcion;
    public $pricetype_id, $increment;

    public $updateFormAlmacen = 0;
    public $newalmacen_id;
    public $newcantidad = 0;

    protected $listeners = ['delete', 'delete_almacen'];

    protected function rules()
    {
        return [
            'producto.name' => [
                'required', 'min:3', new CampoUnique('productos', 'name', $this->producto->id, true)
            ],
            'producto.marca_id' => ['required', 'exists:marcas,id'],
            'producto.modelo' => ['required'],
            'producto.pricebuy' => ['required', 'decimal:0,2', 'min:0'],
            'producto.igv' => ['required', 'decimal:0,2', 'min:0'],
            'producto.unit_id' => ['required', 'exists:units,id'],
            'producto.category_id' => ['required', 'exists:categories,id'],
            'producto.subcategory_id' => ['nullable', 'exists:subcategories,id'],
            // 'producto.selectedAlmacens' => ['required', 'array', 'min:1', 'exists:almacens,id'],
            'producto.almacenarea_id' => ['required', 'exists:almacenareas,id'],
            'producto.estante_id' => ['required', 'exists:estantes,id'],
            'producto.publicado' => ['nullable', 'min:0', 'max:1'],
            // 'producto.tribute.id' => ['required', 'exists:tributes,id'],
        ];
    }

    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->almacens = Almacen::whereNotIn('id', $this->producto->almacens->pluck('id'))
            ->orderBy('name', 'asc')->get();
        $this->typegarantia = new Typegarantia();
        $this->seriesalmacen = $this->producto->seriesdisponibles;
    }

    public function render()
    {
        $typegarantias = Typegarantia::orderBy('name', 'asc')->get();
        $pricetypes = Pricetype::orderBy('name', 'asc')->get();
        $caracteristicas = Caracteristica::orderBy('name', 'asc')->get();

        return view('almacen::livewire.productos.show-producto', compact('typegarantias', 'pricetypes', 'caracteristicas'));
    }

    public function updatedProductoPublicado($value)
    {

        $this->producto->publicado = $value ? $value : 0;
        $this->producto->save();
        $this->dispatchBrowserEvent('updated');
    }

    public function updatedTypegarantiaId($value)
    {
        $this->reset(['typegarantia', 'time']);
        if ($value) {
            $this->typegarantia = Typegarantia::find($value);
            $this->time = $this->typegarantia->time;
        }
    }

    public function updatedSearchseriealmacen($value)
    {
        if (count($this->searchseriealmacen)) {

            $this->seriesalmacen = $this->producto->seriesdisponibles()
                ->whereIn('almacen_id', $this->searchseriealmacen)
                ->get();
        } else {
            $this->seriesalmacen = $this->producto->seriesdisponibles;
        }
    }

    public function update_image_default(Image $image)
    {
        $this->producto->images()->update(['default' => 0]);
        $image->default = 1;
        $image->save();
        $this->producto->refresh();
        $this->dispatchBrowserEvent('updated');
    }

    public function add_image()
    {
        $validatedata = $this->validate([
            'producto.id' => ['required', 'exists:productos,id'],
            'imagen' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120']
        ]);

        $countImages = $this->producto->images()->count();

        if ($countImages >= 5) {
            $this->addError('imagen', 'Exediste el límite de imágenes por producto.');
            return false;
        }

        if ($this->imagen) {

            if (!Storage::exists('productos')) {
                Storage::makeDirectory('productos');
            }

            $compressedImage = ImageIntervention::make($this->imagen->getRealPath())
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

            $default = $countImages == 0 ? 1 : 0;

            $this->producto->images()->create([
                'url' => $filename,
                'default' => $default
            ]);

            $this->identificador = rand();
            $this->reset(['imagen']);
            $this->producto->refresh();
            $this->dispatchBrowserEvent('created');
        }
    }

    public function delete_image(Image $image)
    {
        if ($image->default) {
            $imageDefault =  $this->producto->images()
                ->where('id', '!=', $image->id)->first();
            if ($imageDefault) {
                $imageDefault->default = 1;
                $imageDefault->save();
            }
        }
        $image->delete();

        if (Storage::exists('productos/' . $image->url)) {
            Storage::delete('productos/' . $image->url);
        }
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function openmodal()
    {
        $this->resetValidation(['newcantidad', 'newalmacen_id']);
        $this->reset(['newcantidad', 'newalmacen_id', 'updateFormAlmacen']);
        $this->almacens = Almacen::whereNotIn('id', $this->producto->almacens->pluck('id'))
            ->orderBy('name', 'asc')->get();
        $this->openalmacen = true;
    }

    public function add_almacen()
    {
        $validatedata = $this->validate([
            'producto.id' => ['required', 'exists:productos,id'],
            'newalmacen_id' => [
                'required', 'exists:almacens,id',
                Rule::unique('almacen_producto', 'almacen_id')
                    ->where(fn (Builder $query) => $query
                        ->where('producto_id', $this->producto->id))
                    ->ignore($this->newalmacen_id, 'almacen_id')
            ],
            'newcantidad' => ['required', 'decimal:0,2', 'min:0']
        ]);

        $event = 'created';

        if ($this->updateFormAlmacen) {

            $event = 'updated';
            $countSeries = $this->producto->seriesdisponibles
                ->where('almacen_id', $this->newalmacen_id)->count();

            if ($this->newcantidad >= $countSeries) {
                $this->producto->almacens()->updateExistingPivot($this->newalmacen_id, [
                    'updated_at' => now('America/Lima'),
                    'cantidad' => $this->newcantidad,
                    'user_id' => Auth::user()->id
                ]);
            } else {
                $this->addError('newcantidad', "Cantidad es menor a las series disonibles ($countSeries).");
                return false;
            }
        } else {
            $this->producto->almacens()->attach($this->newalmacen_id, [
                'date' => now('America/Lima'),
                'created_at' => now('America/Lima'),
                'cantidad' => $this->newcantidad,
                'user_id' => Auth::user()->id
            ]);
        }

        $this->reset(['newcantidad', 'newalmacen_id', 'openalmacen']);
        $this->resetValidation(['newcantidad', 'newalmacen_id']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent($event);
    }

    public function editalmacen(Almacen $almacen)
    {
        $this->updateFormAlmacen = 1;
        $this->resetValidation(['newcantidad', 'newalmacen_id']);
        $this->almacens = Almacen::where('id', $almacen->id)->get();
        $this->newalmacen_id = $almacen->id;
        $this->newcantidad = $this->producto->almacens()->find($almacen->id)->pivot->cantidad;
        $this->openalmacen = true;
    }

    public function confirm_delete_almacen(Almacen $almacen)
    {
        $this->dispatchBrowserEvent('producto_almacen.confirmDelete', $almacen);
    }

    public function delete_almacen(Almacen $almacen)
    {

        DB::beginTransaction();

        try {
            $result = $this->producto->almacens()->find($almacen->id);

            if ($result->pivot->cantidad > 0) {
                $countSeries = $this->producto->seriesdisponibles
                    ->where('almacen_id', $almacen->id)->count();

                if ($countSeries > 0) {
                    // $this->producto->series()->where('almacen_id', $almacen->id)
                    //     ->update(['delete' => 1]);
                    $this->producto->series()->where('almacen_id', $almacen->id)->delete();
                }
                $this->producto->almacens()->detach($almacen);
            } else {
                $this->producto->almacens()->detach($almacen);
            }

            DB::commit();
            $this->producto->refresh();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }



    public function modalespefificacion()
    {
        $this->selectedEspecificacion = $this->producto->especificaciones()
            ->pluck('especificacion_id', 'caracteristica_id',)->toArray();
        $this->openespecificacion = true;
    }

    public function add_especificacion()
    {
        $this->producto->especificaciones()->syncWithPivotValues($this->selectedEspecificacion, [
            'user_id' => Auth::user()->id,
            'created_at' => now('America/Lima')
        ]);

        $this->reset(['selectedEspecificacion', 'openespecificacion']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('updated');
    }

    public function delete_especificacion(Especificacion $especificacion)
    {
        $this->producto->especificaciones()->detach($especificacion);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function add_serie()
    {
        $this->serie = trim(mb_strtoupper($this->serie, "UTF-8"));
        $validatedata = $this->validate([
            'producto.id' => ['required', 'exists:productos,id'],
            'almacen_id' => ['required', 'exists:almacens,id'],
            'serie' => [
                'required', 'min:2',
                new CampoUnique('series', 'serie', null, true)
            ]
        ]);

        $countSeries = $this->producto->seriesdisponibles
            ->where('almacen_id', $this->almacen_id)->count();

        $cantidadAlmacen = $this->producto->almacens
            ->find($this->almacen_id)->pivot->cantidad;

        if ($countSeries >= $cantidadAlmacen) {
            $this->addError('serie', 'Serie sobrepase el stock disponible en almacén.');
            return false;
        }

        $this->producto->series()->create([
            'date' => now('America/Lima'),
            'serie' => $this->serie,
            'almacen_id' => $this->almacen_id,
            'user_id' => Auth::user()->id,
            'created_at' => now('America/Lima'),
        ]);

        $this->reset(['serie', 'almacen_id']);
        $this->resetValidation(['serie', 'almacen_id']);
        $this->producto->refresh();
        if (count($this->searchseriealmacen)) {
            $this->seriesalmacen = $this->producto->seriesdisponibles()
                ->whereIn('almacen_id', $this->searchseriealmacen)
                ->get();
        } else {
            $this->seriesalmacen = $this->producto->seriesdisponibles;
        }
        $this->dispatchBrowserEvent('created');
    }

    public function delete_serie(Serie $serie)
    {
        $serie->deleteOrFail();
        $this->producto->refresh();
        if (count($this->searchseriealmacen)) {
            $this->seriesalmacen = $this->producto->seriesdisponibles()
                ->whereIn('almacen_id', $this->searchseriealmacen)
                ->get();
        } else {
            $this->seriesalmacen = $this->producto->seriesdisponibles;
        }

        $this->dispatchBrowserEvent('deleted');
    }

    public function add_garantia()
    {
        $validatedata = $this->validate([
            'producto.id' => ['required', 'exists:productos,id'],
            'typegarantia_id' => [
                'required', 'exists:typegarantias,id',
                Rule::unique('garantiaproductos', 'typegarantia_id')
                    ->where('producto_id', $this->producto->id)
                // ->whereNull('deleted_at')
            ],
            'time' => ['required', 'numeric', 'min:1']
        ]);

        $countGarantia = $this->producto->garantiaproductos
            ->where('typegarantia_id', $this->typegarantia_id)->count();

        $this->producto->garantiaproductos()->create([
            'time' => $this->time,
            'typegarantia_id' => $this->typegarantia_id,
            'user_id' => Auth::user()->id,
            'created_at' => now('America/Lima')
        ]);


        $this->reset(['time', 'typegarantia_id']);
        $this->resetValidation(['time', 'typegarantia_id']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('created');
    }

    public function delete_garantia(Garantiaproducto $garantiaproducto)
    {
        $garantiaproducto->deleteOrFail();
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function add_detalle()
    {
        $validatedata = $this->validate([
            'producto.id' => ['required', 'exists:productos,id'],
            'titulo' => ['required', 'min:3'],
            'descripcion' => ['required', 'min:3']
        ]);

        $this->producto->detalleproductos()->create([
            'title' => $this->titulo,
            'descripcion' => $this->descripcion,
            'created_at' => now('America/Lima')
        ]);

        $this->reset(['titulo', 'descripcion']);
        $this->resetValidation(['titulo', 'descripcion']);
        $this->emit("resetCKEditor");
        $this->producto->refresh();
        $this->dispatchBrowserEvent('created');
    }



    public function confirm_delete(Producto $producto)
    {
        $this->dispatchBrowserEvent('producto.confirmDelete', $producto);
    }

    public function clearImage()
    {
        $this->reset(['imagen']);
        $this->resetValidation(['imagen']);
        $this->identificador = rand();
    }

    public function hydrate()
    {
        $this->dispatchBrowserEvent('render-showproducto-select2');
    }
}
