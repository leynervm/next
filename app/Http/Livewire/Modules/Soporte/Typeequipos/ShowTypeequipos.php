<?php

namespace App\Http\Livewire\Modules\Soporte\Typeequipos;

use App\Rules\CampoUnique;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Modules\Soporte\Entities\Typeequipo;
use Intervention\Image\ImageManagerStatic as Image;

class ShowTypeequipos extends Component
{

    use WithFileUploads, WithPagination;

    public $open = false;
    public $typeequipo, $logo, $identificador;

    protected $listeners = ['render'];

    public function mount()
    {
        $this->identificador = rand();
        $this->typeequipo = new Typeequipo();
    }

    protected function rules()
    {
        return [
            'typeequipo.name' => ['required', 'min:2', 'max:100', new CampoUnique('typeequipos', 'name', $this->typeequipo->id)],
            'logo' => ['nullable', 'string', 'starts_with:data:image'],
        ];
    }

    public function render()
    {
        $typeequipos = Typeequipo::orderBy('name', 'asc')->paginate();
        return view('livewire.modules.soporte.typeequipos.show-typeequipos', compact('typeequipos'));
    }

    public function updatedOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset('logo');
            $this->identificador = rand();
        }
    }

    public function edit(Typeequipo $typeequipo)
    {
        $this->typeequipo = $typeequipo;
        $this->open = true;
    }

    public function update()
    {
        $this->typeequipo->name = toStrUppercase($this->typeequipo->name);
        $this->validate();
        $logoURL = null;

        if ($this->logo) {
            if (!Storage::directoryExists('images/typeequipos/')) {
                Storage::makeDirectory('images/typeequipos/');
            }

            $allowedMimes = ['jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
            $imageData = explode(',', $this->logo)[1] ?? $this->logo;
            $decodedImage = base64_decode($imageData);
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($decodedImage);

            if (!in_array($mime, $allowedMimes)) {
                $this->addError('logo', 'Formato no soportado (solo JPEG, PNG, WebP)');
                return false;
            }

            $compressedImage = Image::make($decodedImage)
                ->resize(100, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->orientate()->encode('webp', 90);

            $logoURL = uniqid('typeequipo_') . '.webp';
            $compressedImage->save(public_path('storage/images/typeequipos/' . $logoURL));

            if ($compressedImage->filesize() > 1048576) { //1MB
                $compressedImage->destroy();
                $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
                return false;
            }

            if ($this->typeequipo->image) {
                Storage::delete(getTypeequipoURL($this->typeequipo->image->url));
                $this->typeequipo->image()->delete();
            }

            $this->typeequipo->image()->create([
                'url' => $logoURL,
                'default' => 1
            ]);
        }

        $this->typeequipo->save();
        $this->reset(['logo', 'open']);
        $this->identificador = rand();
        $this->resetValidation();
    }

    public function delete($id)
    {
        if ($id) {
            $typeequipo = Typeequipo::withCount('equipos')->find($id);
            if ($typeequipo) {
                if ($typeequipo->equipos_count > 0) {
                    $typeequipo->delete();
                } else {
                    Storage::delete('images/typeequipos/' . $typeequipo->image->url);
                    $typeequipo->image()->delete();
                    $typeequipo->forceDelete();
                }
                return $this->dispatchBrowserEvent('deleted');
            }
        }

        return $this->dispatchBrowserEvent('toast', toastJSON('NO SE ENCONTRÓ EL REGISTRO', 'error'));
    }

    public function deletelogo()
    {
        // $this->authorize('admin.soporte.typeequipos.edit');
        if ($this->typeequipo->image) {
            Storage::delete(getTypeequipoURL($this->typeequipo->image->url));
            $this->typeequipo->image()->delete();
            $this->typeequipo->refresh();
        }
    }

    public function clearImage()
    {
        $this->reset(['logo', 'identificador']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function updatedLogo($file)
    {
        try {
            // $url = $file->temporaryUrl();
            $this->resetValidation();
        } catch (\Exception $e) {
            $this->reset(['logo']);
            $this->addError('logo', $e->getMessage());
            return;
        }
    }
}
