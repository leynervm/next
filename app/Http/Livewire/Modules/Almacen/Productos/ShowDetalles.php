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
use Livewire\WithPagination;

class ShowDetalles extends Component
{

    use WithPagination, WithFileUploads, AuthorizesRequests;

    public Producto $producto;
    public $open = false;
    public $openimage = false;
    public $imagen, $identificador;
    public $searchcaracteristica = '';

    public $selectedEspecificacion = [];

    // protected $queryString = [
    //     'searchcaracteristica' => ['except' => '', 'as' => 'search_especificacion'],
    // ];

    protected function rules()
    {
        return [
            'producto.comentario' => ['nullable', 'string']
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
    }

    public function render()
    {

        $caracteristicas = Caracteristica::with('especificacions');

        if (trim($this->searchcaracteristica) != '') {
            $caracteristicas->where('name', 'ilike', '%' . $this->searchcaracteristica . '%')
                ->orWhereHas('especificacions', function ($query) {
                    $query->where('name', 'ilike', '%' . $this->searchcaracteristica . '%');
                });
        }

        $caracteristicas = $caracteristicas->orderBy('orden', 'asc')->paginate(15, ['*'], 'caracteristicasPage');
        return view('livewire.modules.almacen.productos.show-detalles', compact('caracteristicas'));
    }

    public function updatedSearchcaracteristica()
    {
        $this->resetPage('caracteristicasPage');
    }

    public function openmodal()
    {
        $this->authorize('admin.almacen.productos.especificaciones');
        $this->resetPage('caracteristicasPage');
        $this->reset(['searchcaracteristica']);
        $this->selectedEspecificacion = $this->producto->especificacions()
            ->pluck('especificacion_id', 'caracteristica_id')->toArray();
        $this->open = true;
    }

    public function saveespecificacion()
    {
        $this->authorize('admin.almacen.productos.especificaciones');

        $arrayPivotValues = [];
        $especif_in_bd = $this->producto->especificacions()
            ->pluck('especificacion_id')->toArray();
        $orden = ($this->producto->especificacions()->max('orden') ?? 0) + 1;
        foreach ($this->selectedEspecificacion as $item) {
            if (in_array($item, $especif_in_bd)) {
                $arrayPivotValues[(int) $item] = [
                    'orden' => array_search((int) $item, $especif_in_bd) + 1
                ];
            } else {
                $arrayPivotValues[(int) $item] = [
                    'orden' => $orden
                ];
                $orden++;
            }
        }

        // dd($this->selectedEspecificacion, $arrayPivotValues);
        // dd($this->selectedEspecificacion, $item, $value);
        $this->producto->especificacions()->sync($arrayPivotValues);
        $this->reset(['selectedEspecificacion', 'searchcaracteristica', 'open']);
        $this->resetPage('caracteristicasPage');
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
            // 'imagen' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'dimensions:min_width=500,min_height=500']
            'imagen' => ['required', 'string', 'starts_with:data:image'],
        ]);

        if (!Storage::exists('images/productos')) {
            Storage::makeDirectory('images/productos');
        }

        $allowedMimes = ['jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];

        try {
            $imageData = explode(',', $this->imagen)[1] ?? $this->imagen;
            $decodedImage = base64_decode($imageData);
            // dd($imageData);
            // $maxSize = 5 * 1024; // 5MB en KB
            // if (strlen($decodedImage) / 1024 > $maxSize) {
            //     // Rechazar
            // }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($decodedImage);

            if (!in_array($mime, $allowedMimes)) {
                $this->addError('imagen', 'Formato no soportado (solo JPEG, PNG, WebP)');
                return false;
            }

            $compressedImage = ImageIntervention::make($decodedImage)
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->orientate()->encode('webp', 90);
            // Generar Imagen Responsive
            $compressImageMobile = ImageIntervention::make($decodedImage)
                ->resize(320, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });


            $empresa = view()->shared('empresa');
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

                // Generar Imagen Responsive
                $margin = $empresa->alignmark !== 'center' ? 7 : 0;
                $mark = ImageIntervention::make($urlMark)->resize(60, 60, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->orientate();
                $compressImageMobile->insert($mark, $empresa->alignmark, $margin, $margin);
            }

            if ($compressedImage->filesize() > 1048576) { //1MB
                $compressedImage->destroy();
                $this->addError('imagen', "La imagen excede el tamaño permitido 1MB, tamaño actual " . $compressedImage->filesize());
                return false;
            }

            $filename = uniqid('producto_') . '.webp';
            $compressedImage->save(public_path('storage/images/productos/' . $filename));

            $filenameMobile = uniqid('producto_mobile_') . '.webp';
            $compressImageMobile->save(public_path('storage/images/productos/' . $filenameMobile));

            $countImages = $this->producto->images()->count();
            $default = $countImages == 0 ? 1 : 0;
            $orden = $this->producto->images()->max('orden') ?? 0;
            $this->producto->images()->create([
                'url' => $filename,
                'urlmobile' => $filenameMobile,
                'default' => $default,
                'orden' => 1 + $orden
            ]);

            Self::clearImage();
            $this->producto->refresh();
            $this->dispatchBrowserEvent('created');
        } catch (\Exception $e) {
            $this->addError('imagen', "Error al procesar la imagen " . $e->getMessage());
        }
    }

    public function deleteimage(Image $image)
    {
        $this->authorize('admin.almacen.productos.images');
        if ($image->default) {
            $imageDefault = $this->producto->images()->where('id', '<>', $image->id)->first();
            if ($imageDefault) {
                $imageDefault->default = 1;
                $imageDefault->save();
            }
        }
        $image->delete();

        if (Storage::exists('images/productos/' . $image->url)) {
            Storage::delete('images/productos/' . $image->url);
        }
        if (Storage::exists('images/productos/' . $image->urlmobile)) {
            Storage::delete('images/productos/' . $image->urlmobile);
        }
        $this->producto->refresh();
        $this->dispatchBrowserEvent('deleted');
    }


    public function clearImage()
    {
        $this->reset(['imagen', 'identificador']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function updatedImagen($file)
    {
        try {
            // dd($this->imagen);
            // $url = $file->temporaryUrl();
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->reset(['imagen']);
            $this->addError('imagen', $e->getMessage());
            return;
        }
    }
}
