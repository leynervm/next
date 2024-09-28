<?php

namespace Modules\Soporte\Http\Livewire\Equipos;

use App\Models\Equipo;
use App\Rules\CampoUnique;
use App\Rules\Letter;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class CreateEquipo extends Component
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
                'required', 'min:3', 'max:100', new Letter,
                new CampoUnique('equipos', 'name'),
            ],
            'logo' => 'nullable|file|mimes:jpeg,png,gif|max:5120'
        ];
    }

    public function render()
    {
        return view('soporte::livewire.equipos.create-equipo');
    }

    public function mount()
    {
        $this->identificador = rand();
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
        $this->name = trim($this->name);
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
        }

        $equipo = Equipo::where('name', mb_strtoupper($this->name, "UTF-8"))
            ->where('delete', 1)->first();

        if ($equipo) {

            if ($equipo->logo) {
                Storage::delete('equipos/' . $equipo->logo);
            }

            $equipo->delete = 0;
            $equipo->logo = $logoURL;
            $equipo->save();
        } else {
            Equipo::create([
                'name' => $this->name,
                'logo' => $logoURL,
            ]);
        }
        $this->emitTo('soporte::equipos.show-equipos', 'render');
        $this->identificador = rand();
        $this->reset(['name', 'logo', 'open']);
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
