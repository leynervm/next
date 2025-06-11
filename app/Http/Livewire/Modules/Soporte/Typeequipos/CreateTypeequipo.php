<?php

namespace App\Http\Livewire\Modules\Soporte\Typeequipos;

use App\Rules\CampoUnique;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Modules\Soporte\Entities\Typeequipo;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\WithFileUploads;

class CreateTypeequipo extends Component
{

    use WithFileUploads;

    public $open = false;
    public $name, $logo, $identificador;

    protected $listeners = ['errorImage'];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', new CampoUnique('typeequipos', 'name', null, true)],
            'logo' => ['nullable', 'string', 'starts_with:data:image'],
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
    }

    public function render()
    {
        return view('livewire.modules.soporte.typeequipos.create-typeequipo');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name', 'logo']);
            $this->identificador = rand();
        }
    }

    public function save($closemodal = false)
    {

        $this->name = toStrUppercase($this->name);
        $this->validate();
        $logoURL = null;
        DB::beginTransaction();
        try {
            $typeequipo = Typeequipo::with('image')->withTrashed()->where('name', $this->name)->first();

            if ($typeequipo) {
                if ($typeequipo->trashed()) {
                    $typeequipo->restore();
                    if ($typeequipo->image) {
                        Storage::delete('images/typeequipos/' . $typeequipo->image->url);
                        $typeequipo->image()->delete();
                    }
                }
            } else {
                $typeequipo = Typeequipo::create([
                    'name' => $this->name,
                ]);
            }

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

                $typeequipo->image()->create([
                    'url' => $logoURL,
                    'default' => 1
                ]);
            }

            DB::commit();
            $this->emitTo('modules.soporte.typeequipos.show-typeequipos', 'render');
            $this->dispatchBrowserEvent('created');
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept('open');
            }
            Self::clearImage();
            $this->resetValidation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            $this->addError('imagen', "Error al procesar la imagen " . $e->getMessage());
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
            $this->addError('imagen', "Error al procesar la imagen " . $e->getMessage());
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
