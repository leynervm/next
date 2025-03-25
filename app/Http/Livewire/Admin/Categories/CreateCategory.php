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
    public $name, $icon, $image, $extensionimage;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:100', new CampoUnique('categories', 'name', null, true)],
            'icon' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg|webp);base64,([A-Za-z0-9+\/=]+)$/']
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
            $this->reset('name', 'open', 'icon');
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.almacen.categorias.create');
        $this->name = mb_strtoupper(trim($this->name), "UTF-8");
        $this->validate();
        DB::beginTransaction();
        try {
            $orden = Category::max('orden') ?? 0;
            $category = Category::withTrashed()
                ->where('name', $this->name)->first();

            if ($category) {
                $category->orden = $orden + 1;
                $category->icon = $this->icon;
                $category->restore();
                if ($category->image) {
                    if (Storage::exists('images/categories/' . $category->image->url)) {
                        Storage::delete('images/categories/' . $category->image->url);
                    }
                    $category->image->delete();
                }
            } else {
                $category = Category::create([
                    'name' => $this->name,
                    'icon' => $this->icon,
                    'orden' => $orden + 1
                ]);
            }

            $urlimage = null;
            if ($this->image) {
                $imageLogo = $this->image;
                list($type, $imageLogo) = explode(';', $imageLogo);
                list(, $imageLogo) = explode(',', $imageLogo);
                $imageLogo = base64_decode($imageLogo);

                if (!Storage::directoryExists('images/categories/')) {
                    Storage::makeDirectory('images/categories/');
                }

                $compressedImage = Image::make($imageLogo)
                    ->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->orientate()->encode('webp', 90);

                // $compressedImage = Image::make($imageLogo)->resize(300, 300, function ($constraint) {
                //     $constraint->aspectRatio();
                //     $constraint->upsize();
                // })->orientate()->encode('jpg', 30);

                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('image', 'La imagen excede el tamaño máximo permitido.');
                    return false;
                }
                $urlimage = uniqid('category_') . '.webp';
                $compressedImage->save(public_path('storage/images/categories/' . $urlimage));
                $category->image()->create([
                    'url' => $urlimage,
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
}
