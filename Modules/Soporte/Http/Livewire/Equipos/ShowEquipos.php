<?php

namespace Modules\Soporte\Http\Livewire\Equipos;

use App\Models\Equipo;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ShowEquipos extends Component
{

    use WithPagination, WithFileUploads;

    public $open = false;
    public $isUploading = false;
    public $logo;
    public $identificador;
    public $equipo;

    protected $listeners = ['render', 'errorImage', 'deleteEquipo' => 'delete'];

    public function mount()
    {
        $this->identificador = rand();
    }

    protected function rules()
    {
        return [
            'equipo.name' => [
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('equipos', 'name', $this->equipo->id),
            ],
            'logo' => 'nullable|file|mimes:jpeg,png,gif|max:5120',
        ];
    }

    public function render()
    {
        $equipos = Equipo::where('delete', 0)->orderBy('name', 'asc')->paginate();
        return view('soporte::livewire.equipos.show-equipos', compact('equipos'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->identificador = rand();
            $this->reset(['logo']);
            $this->resetValidation();
        }
    }

    public function edit(Equipo $equipo)
    {
        $this->equipo = $equipo;
        $this->open = true;
    }

    public function update()
    {
        $this->equipo->name = trim($this->equipo->name);
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

            $filename = uniqid('equipo_') . '.' . $this->logo->getClientOriginalExtension();
            $compressedImage->save(public_path('storage/equipos/' . $filename));

            if ($compressedImage->filesize() > 1048576) { //1MB
                $compressedImage->destroy();
                $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
            }
            $logoURL = $filename;

            if ($this->equipo->logo) {
                Storage::delete('equipos/' . $this->equipo->logo);
            }

            $this->equipo->logo = $logoURL;
        }

        $this->equipo->save();
        $this->reset(['logo', 'open']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function confirmDelete(Equipo $equipo)
    {
        $this->dispatchBrowserEvent('soporte::equipos.confirmDelete', $equipo);
    }

    public function delete(Equipo $equipo)
    {
        if ($equipo->logo) {
            Storage::delete('equipos/' . $equipo->logo);
        }
        $equipo->delete = 1;
        $equipo->logo = null;
        $equipo->save();
        $this->dispatchBrowserEvent('soporte::equipos.deleted');
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
