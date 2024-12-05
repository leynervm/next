<?php

namespace App\Http\Livewire\Admin\Categories;

use App\Models\Category;
use App\Rules\CampoUnique;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Intervention\Image\ImageManagerStatic as Image;
use Livewire\WithPagination;

class ShowCategories extends Component
{

    use AuthorizesRequests, WithPagination;

    public $open = false;
    public $category, $image, $extensionimage;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'category.name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                new CampoUnique('categories', 'name', $this->category->id, true),
            ],
            'category.icon' => [
                'nullable',
                'string',
                'image' => ['nullable', 'string', 'regex:/^data:image\/(png|jpg|jpeg);base64,([A-Za-z0-9+\/=]+)$/']
            ],
        ];
    }

    public function mount()
    {
        $this->category = new Category();
    }

    public function render()
    {
        $categories = Category::with(['image', 'subcategories'])->orderBy('orden', 'asc')->orderBy('name', 'asc')->paginate();
        return view('livewire.admin.categories.show-categories', compact('categories'));
    }

    public function edit(Category $category)
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->category = $category;
        $this->open = true;
    }

    public function update()
    {
        $this->authorize('admin.almacen.categorias.edit');
        $this->category->name = trim($this->category->name);
        DB::beginTransaction();
        try {
            $this->validate();
            $this->category->save();
            if ($this->image) {
                $imageLogo = $this->image;
                list($type, $imageLogo) = explode(';', $imageLogo);
                list(, $imageLogo) = explode(',', $imageLogo);
                $imageLogo = base64_decode($imageLogo);

                if (!Storage::directoryExists('images/categories/')) {
                    Storage::makeDirectory('images/categories/');
                }

                $compressedImage = Image::make($imageLogo)->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->orientate()->encode('jpg', 30);
                if ($compressedImage->filesize() > 1048576) { //1MB
                    $compressedImage->destroy();
                    $this->addError('image', 'La imagen excede el tamaño máximo permitido.');
                    return false;
                }

                $urlimage = uniqid('category_') . '.' . $this->extensionimage;
                $compressedImage->save(public_path('storage/images/categories/' . $urlimage));
                if ($this->category->image) {
                    if (Storage::exists('images/categories/' . $this->category->image->url)) {
                        Storage::delete('images/categories/' . $this->category->image->url);
                    }
                    $this->category->image->url = $urlimage;
                    $this->category->image->save();
                } else {
                    $this->category->image()->create([
                        'url' => $urlimage,
                        'default' => 1
                    ]);
                }
            }
            DB::commit();
            $this->resetValidation();
            $this->resetExcept(['category']);
            $this->category->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function delete(Category $category)
    {

        $this->authorize('admin.almacen.categorias.delete');
        DB::beginTransaction();
        try {
            $category->subcategories()->detach();
            if ($category->image) {
                if (Storage::exists('images/categories/' . $category->image->url)) {
                    Storage::delete('images/categories/' . $category->image->url);
                }
                $category->image->delete();
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
    }

    public function deleteimagecategory()
    {
        if ($this->category->image) {
            if (Storage::exists('images/categories/' . $this->category->image->url)) {
                Storage::delete('images/categories/' . $this->category->image->url);
            }
            $this->category->image->delete();
            $this->category->refresh();
        }
    }
}
