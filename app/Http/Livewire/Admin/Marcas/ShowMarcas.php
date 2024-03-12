<?php

namespace App\Http\Livewire\Admin\Marcas;

use App\Models\Marca;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Intervention\Image\ImageManagerStatic as Image;

class ShowMarcas extends Component
{

    use WithPagination, WithFileUploads, AuthorizesRequests;

    public $marca;
    public $logo;
    public $open = false;
    public $isUploading = false;
    public $identificador;


    protected $listeners = ['render', 'errorImage'];

    protected function rules()
    {
        return [
            'marca.name' => [
                'required', 'min:2', 'max:100', 'unique:marcas,name,' . $this->marca->id,
            ],
            'logo' => [
                'nullable', 'file', 'mimes:jpeg,png,gif',  'max:5120'
            ]
        ];
    }

    public function mount()
    {
        $this->marca = new Marca();
        $this->identificador = rand();
    }


    public function render()
    {
        $marcas = Marca::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.marcas.show-marcas', compact('marcas'));
    }

    public function update()
    {

        $this->authorize('admin.almacen.marcas.edit');
        $this->marca->name = trim($this->marca->name);
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

                if ($this->marca->image) {
                    Storage::delete('marcas/' . $this->marca->image->url);
                    $this->marca->image()->delete();
                }

                $this->marca->image()->create([
                    'url' => $logoURL,
                    'default' => 1
                ]);
            }

            DB::commit();
            $this->marca->save();
            $this->reset(['logo', 'open']);
            $this->resetValidation();
            $this->identificador = rand();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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
        $this->authorize('admin.almacen.marcas.edit');
        $this->marca = $marca;
        $this->open = true;
    }


    public function delete(Marca $marca)
    {
        $this->authorize('admin.almacen.marcas.delete');
        if ($marca->image) {
            Storage::delete('marcas/' . $marca->image->url);
            $marca->image()->delete();
        }
        $marca->delete();
        $this->dispatchBrowserEvent('deleted');
    }

    public function clearImage()
    {
        $this->authorize('admin.almacen.marcas.edit');
        $this->reset(['logo']);
        $this->resetValidation();
        $this->identificador = rand();
    }

    public function errorImage()
    {
        $this->reset(['logo']);
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
