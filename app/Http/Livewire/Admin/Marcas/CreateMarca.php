<?php

namespace App\Http\Livewire\Admin\Marcas;

use App\Models\Marca;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as Image;

class CreateMarca extends Component
{

    use AuthorizesRequests;
    use WithFileUploads;

    public $open = false;
    public $logo;
    public $identificador;
    public $name;

    protected $listeners = ['errorImage'];

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'min:2',
                'max:100',
                new CampoUnique('marcas', 'name', null, true),
            ],
            'logo' => [
                'nullable',
                'file',
                'mimes:jpeg,png,gif',
                'max:5120'
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
            $this->authorize('admin.almacen.marcas.create');
            $this->resetValidation();
            $this->identificador = rand();
            $this->reset(['name', 'logo']);
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.almacen.marcas.create');
        // $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->validate();
        $logoURL = null;

        DB::beginTransaction();
        try {
            $marca = Marca::onlyTrashed()
                ->where('name', mb_strtoupper(trim($this->name), "UTF-8"))
                ->first();

            if ($marca) {
                $marca->restore();
                if ($marca->image) {
                    Storage::delete('images/marcas/' . $marca->image->url);
                    $marca->image()->delete();
                }
            } else {
                $marca = Marca::create([
                    'name' => $this->name
                ]);
            }

            if ($this->logo) {
                if (!Storage::directoryExists('images/marcas/')) {
                    Storage::makeDirectory('images/marcas/');
                }

                $compressedImage = Image::make($this->logo->getRealPath())
                    ->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 30);

                $logoURL = uniqid('marca_') . '.' . $this->logo->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/images/marcas/' . $logoURL));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
                    return false;
                }

                $marca->image()->create([
                    'url' => $logoURL,
                    'default' => 1
                ]);
            }
            DB::commit();
            $this->resetValidation();
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept('open');
            }
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

    public function updatedLogo($file)
    {
        try {
            $url = $file->temporaryUrl();
        } catch (\Exception $e) {
            $this->reset(['logo']);
            $this->addError('logo', $e->getMessage());
            return;
        }
    }
}
