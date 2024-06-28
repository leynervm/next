<?php

namespace App\Http\Livewire\Modules\Almacen\Productos;

use App\Models\Caracteristica;
use App\Models\Especificacion;
use App\Models\Image;
use App\Models\Producto;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as ImageIntervention;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowDetalles extends Component
{

    use WithFileUploads, AuthorizesRequests;

    public $open = false;
    public $openimage = false;
    public $producto;
    public $imagen, $identificador;

    public $selectedEspecificacion = [];

    public function mount(Producto $producto)
    {
        $this->identificador = rand();
        $this->producto = $producto;
    }

    public function render()
    {

        $caracteristicas = Caracteristica::orderBy('name', 'asc')->get();
        return view('livewire.modules.almacen.productos.show-detalles', compact('caracteristicas'));
    }

    public function openmodal()
    {
        $this->authorize('admin.almacen.productos.especificaciones');
        $this->selectedEspecificacion = $this->producto->especificacions()
            ->pluck('especificacion_id', 'caracteristica_id',)->toArray();
        $this->open = true;
    }

    public function saveespecificacion()
    {
        $this->authorize('admin.almacen.productos.especificaciones');
        $this->producto->especificacions()->syncWithPivotValues($this->selectedEspecificacion, [
            'user_id' => auth()->user()->id,
            'created_at' => now('America/Lima')
        ]);

        $this->reset(['selectedEspecificacion', 'open']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Especificacion $especificacion)
    {
        $this->authorize('admin.almacen.productos.especificaciones');
        $this->producto->especificacions()->detach($especificacion);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }

    public function openmodalimage()
    {
        $this->authorize('admin.almacen.productos.images');
        $this->identificador = rand();
        $this->reset(['imagen']);
        $this->openimage = true;
    }

    public function defaultimage(Image $image)
    {
        $this->authorize('admin.almacen.productos.images');
        $this->producto->images()->update(['default' => 0]);
        $image->default = 1;
        $image->save();
        $this->producto->refresh();
        $this->dispatchBrowserEvent('updated');
    }

    public function saveimage()
    {
        $this->authorize('admin.almacen.productos.images');
        $this->validate([
            'producto.id' => ['required', 'integer', 'min:1', 'exists:productos,id'],
            'imagen' => ['required', 'file', 'mimes:jpg,jpeg,png', 'dimensions:min_width=600,min_height=600']
        ]);

        $countImages = $this->producto->images()->count();

        // if ($countImages >= 5) {
        //     $this->addError('imagen', 'Exediste el límite de imágenes por producto.');
        //     return false;
        // }

        if (!Storage::exists('productos')) {
            Storage::makeDirectory('productos');
        }

        $compressedImage = ImageIntervention::make($this->imagen->getRealPath())
            ->resize(1200, 1200, function ($constraint) {
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
            $mark = ImageIntervention::make($urlMark)->resize($w, $h, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->orientate();
            $compressedImage->insert($mark, $empresa->alignmark, $margin, $margin);
        }

        $filename = uniqid('producto_') . '.' . $this->imagen->getClientOriginalExtension();
        $compressedImage->save(public_path('storage/productos/' . $filename));

        if ($compressedImage->filesize() > 1048576) { //1MB
            $compressedImage->destroy();
            $compressedImage->delete();
            $this->addError('imagen', 'El campo imagen no debe ser mayor que 1 MB.');
            return false;
        }

        $default = $countImages == 0 ? 1 : 0;

        $this->producto->images()->create([
            'url' => $filename,
            'default' => $default
        ]);

        $this->resetValidation();
        $this->identificador = rand();
        $this->reset(['imagen', 'openimage']);
        $this->producto->refresh();
        $this->dispatchBrowserEvent('created');
    }

    public function deleteimage(Image $image)
    {
        $this->authorize('admin.almacen.productos.images');
        if ($image->default) {
            $imageDefault = $this->producto->images()
                ->where('id', '<>', $image->id)->first();
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
