<?php

namespace Modules\Soporte\Http\Livewire\Marcas;

use App\Models\Marca;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ShowMarcas extends Component
{
    use WithPagination, WithFileUploads;

    public $open = false;
    public $isUploading = false;
    public $logo;
    public $identificador;
    public $marca;

    protected $listeners = ['render', 'errorImage', 'deleteMarca' => 'delete'];

    public function mount()
    {
        $this->identificador = rand();
    }

    protected function rules()
    {
        return [
            'marca.name' => [
                'required', 'min:2', 'max:100', new Letter,
                new CampoUnique('marcas', 'name', $this->marca->id),
            ],
            'logo' => 'nullable|file|mimes:jpeg,png,gif|max:5120',
        ];
    }

    public function render()
    {
        $marcas = Marca::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.marcas.show-marcas', compact('marcas'));
    }


    public function update()
    {
        $this->marca->name = trim($this->marca->name);
        $this->validate();
        $logoURL = null;

        if ($this->logo) {

            $compressedImage = Image::make($this->logo->getRealPath())
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->orientate()
                ->encode('jpg', 30);

            $filename = uniqid('marca_') . '.' . $this->logo->getClientOriginalExtension();
            $compressedImage->save(public_path('storage/marcas/' . $filename));

            if ($compressedImage->filesize() > 1048576) { //1MB
                $compressedImage->destroy();
                $compressedImage->delete();
                $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
            }
            $logoURL = $filename;

            if ($this->marca->logo) {
                Storage::delete('marcas/' . $this->marca->logo);
            }

            $this->marca->logo = $logoURL;
        }

        $this->marca->save();
        $this->reset(['logo', 'open']);
        $this->resetValidation();
        $this->identificador = rand();
    }


    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->identificador = rand();
            $this->reset(['logo']);
            $this->resetValidation();
        }
    }

    public function edit(Marca $marca)
    {
        $this->marca = $marca;
        $this->open = true;
    }

    public function confirmDelete(Marca $marca)
    {
        $this->dispatchBrowserEvent('soporte::marcas.confirmDelete', $marca);
    }


    public function delete(Marca $marca)
    {

        if ($marca->logo) {
            Storage::delete('marcas/' . $marca->logo);
        }
        $marca->delete = 1;
        $marca->logo = null;
        $marca->save();
        $this->dispatchBrowserEvent('soporte::marcas.deleted');
    }

    public function clearImage()
    {
        $this->reset(['logo']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function errorImage()
    {
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
