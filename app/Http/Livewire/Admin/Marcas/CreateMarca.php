<?php

namespace App\Http\Livewire\Admin\Marcas;

use App\Models\Marca;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

class CreateMarca extends Component
{

    use WithFileUploads;

    public $open = false;
    public $isUploading = false;
    public $logo;
    public $identificador;
    public $name;

    protected $listeners = ['errorImage'];

    protected function rules()
    {
        return [
            'name' => [
                'required', 'min:2', 'max:100', 'unique:marcas,name',
            ],
            'logo' => [
                'nullable', 'file', 'mimes:jpeg,png,gif', 'max:5120'
            ]
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
    }

    public function render()
    {
        return view('livewire.admin.marcas.create-marca');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->identificador = rand();
            $this->reset(['name', 'logo']);
        }
    }

    public function save()
    {
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->validate();
        $logoURL = null;

        DB::beginTransaction();
        try {
            if ($this->logo) {
                $compressedImage = Image::make($this->logo->getRealPath())
                    ->resize(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 30);

                $filename = uniqid('marca_') . '.' . $this->logo->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/marcas/' . $filename));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $compressedImage->delete();
                    $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
                }
                $logoURL = $filename;
            }

            $marca = Marca::where('name', $this->name)->first();

            if ($marca) {
                if ($marca->trashed()) {
                    $marca->restore();
                    if ($marca->image) {
                        Storage::delete('marcas/' . $marca->image->url);
                        $marca->image()->delete();
                    }
                }
            } else {
                $marca = Marca::create(['name' => $this->name]);
                if ($this->logo) {
                    $marca->image()->create([
                        'url' => $logoURL,
                        'default' => 1
                    ]);
                }
            }
            
            DB::commit();
            $this->resetValidation();
            $this->reset();
            $this->emitTo('admin.marcas.show-marcas', 'render');
            $this->identificador = rand();
            $this->dispatchBrowserEvent('created');
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
        $this->reset(['logo']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function errorImage()
    {
        $this->isUploading = false;
        $this->reset(['logo']);
        $this->identificador = rand();
    }

    public function updatedLogo()
    {

        $this->resetValidation();

        if ($this->logo && !in_array($this->logo->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
            $this->isUploading = false;
            $this->reset(['logo']);
            $this->identificador = rand();
            $this->addError('logo', 'El tipo de archivo no es válido. Solo se permiten imágenes JPG, JPEG y PNG.');
        }
    }
}
