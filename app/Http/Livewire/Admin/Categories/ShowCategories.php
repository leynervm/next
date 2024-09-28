<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Helpers\FormatoPersonalizado;
use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ShowCategories extends Component
{

    use AuthorizesRequests, WithPagination, WithFileUploads;

    public $open = false;
    public $category, $logo;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'category.name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                new CampoUnique('categories', 'name', $this->category->id, true),
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
        $this->category = new Category();
    }

    public function render()
    {

        $categories = Category::with('subcategories')->orderBy('orden', 'asc')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.categories.show-categories', compact('categories'));
    }

    public function edit(Category $category)
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->reset(['logo']);
        $this->category = $category;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->category->name = trim($this->category->name);
        $this->validate();
        $this->category->save();
        if ($this->logo) {
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

            if ($this->category->image) {
                Storage::delete('images/categories/' . $this->category->image->url);
                $this->category->image()->delete();
            }

            $this->category->image()->create([
                'url' => $logoURL,
                'default' => 1
            ]);
        }
        $this->reset(['open', 'logo']);
    }


    public function delete(Category $category)
    {

        $this->authorize('admin.almacen.categorias.delete');
        DB::beginTransaction();
        try {
            $category->subcategories()->detach();
            if ($category->image) {
                Storage::delete($category->image->getCategoryURL());
                $category->image()->delete();
            }

            $category->delete();
            DB::commit();
            $this->dispatchBrowserEvent('deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        // }
    }

    public function clearImage()
    {
        $this->reset(['logo']);
        $this->resetValidation();
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

    public function deletelogo()
    {
        $this->authorize('admin.almacen.categorias.delete');
        if ($this->category->image) {
            Storage::delete($this->category->image->getCategoryURL());
            $this->category->image()->delete();
            $this->category->refresh();
        }
    }
}
