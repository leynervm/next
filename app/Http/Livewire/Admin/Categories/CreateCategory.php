<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{

    use AuthorizesRequests, WithFileUploads;

    public $open = false;
    public $name, $logo;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                new CampoUnique('categories', 'name', null, true),
            ],
            'logo' => [
                'nullable',
                'file',
                'mimes:jpeg,png,gif',
                'max:5120'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.categories.create-category');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->authorize('admin.almacen.categorias.create');
            $this->resetValidation();
            $this->reset('name', 'open', 'logo');
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.almacen.categorias.create');
        $this->name = trim($this->name);
        $this->validate();
        DB::beginTransaction();
        try {
            $orden = Category::max('orden') ?? 0;
            $category = Category::withTrashed()
                ->where('name', mb_strtoupper($this->name, "UTF-8"))->first();

            if ($category) {
                $category->orden = $orden + 1;
                $category->restore();
            } else {
                $category = Category::create([
                    'name' => $this->name,
                    'orden' => $orden + 1
                ]);
            }

            if ($this->logo) {
                if (!Storage::directoryExists('images/categories/')) {
                    Storage::makeDirectory('images/categories/');
                }

                $compressedImage = Image::make($this->logo->getRealPath())
                    ->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('jpg', 30);

                $logoURL = uniqid('category_') . '.' . $this->logo->getClientOriginalExtension();
                $compressedImage->save(public_path('storage/images/categories/' . $logoURL));

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('logo', 'La imagen excede el tamaño máximo permitido.');
                    return false;
                }

                $category->image()->create([
                    'url' => $logoURL,
                    'default' => 1
                ]);
            }
            DB::commit();
            $this->emitTo('admin.categories.show-categories', 'render');
            $this->resetValidation();
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept(['open']);
            }
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
    }
}
