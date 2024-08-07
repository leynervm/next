<?php

namespace App\Http\Livewire\Modules\Marketplace\Sliders;

use App\Models\Slider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Intervention\Image\ImageManagerStatic as ImageIntervention;

class ShowSliders extends Component
{

    use WithPagination, WithFileUploads, AuthorizesRequests;

    public $open = false;
    public $slider, $image, $id_image;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'slider.link' => ['nullable', 'url'],
            'slider.start' => ['required', 'date'],
            'slider.end' => ['nullable', 'date'],
            'slider.url' => ['required'],
            'image' => [
                'nullable', 'file', 'mimes:jpeg,png,gif', 'max:5120', 'dimensions:min_width=1920,min_height=560'
            ]
        ];
    }

    public function mount()
    {
        $this->slider = new Slider();
        $this->id_image = rand();
    }

    public function render()
    {
        $sliders = Slider::orderBy('orden', 'asc')->paginate();
        return view('livewire.modules.marketplace.sliders.show-sliders', compact('sliders'));
    }

    public function edit(Slider $slider)
    {
        $this->slider = $slider;
        $this->resetValidation();
        $this->resetExcept(['slider']);
        $this->id_image = rand();
        $this->open = true;
    }

    public function save()
    {
        $this->authorize('admin.marketplace.sliders.edit');
        $this->validate();
        if ($this->image) {
            $compressedImage = ImageIntervention::make($this->image->getRealPath())
                ->resize(1920, 560, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->orientate()->encode('jpg', 30);

            $url = uniqid('slider_') . '.' . $this->image->getClientOriginalExtension();
            $compressedImage->save(public_path('storage/images/slider/' . $url));

            if ($compressedImage->filesize() > 2097152) { //2MB
                $compressedImage->destroy();
                $compressedImage->delete();
                $this->addError('image', 'El campo imagen no debe ser mayor que 2MB.');
                return false;
            }

            if ($this->slider->url) {
                Storage::delete($this->slider->getImageURL());
            }

            $this->slider->url = $url;
        }


        $this->slider->save();
        $this->resetValidation();
        $this->resetExcept(['slider']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Slider $slider)
    {
        $this->authorize('admin.marketplace.sliders.delete');
        $slider->delete();
        $this->dispatchBrowserEvent('deleted');
    }

    public function updatestatus(Slider $slider)
    {
        $this->authorize('admin.marketplace.sliders.pause');
        $slider->status = $slider->status == Slider::ACTIVO ? Slider::INACTIVO : Slider::ACTIVO;
        $slider->save();
        $this->dispatchBrowserEvent('updated');
    }

    public function updatedImage($file)
    {
        try {
            $url = $file->temporaryUrl();
        } catch (\Exception $e) {
            $this->reset(['image']);
            $this->addError('image', $e->getMessage());
            return;
        }
    }

    public function clearImage()
    {
        $this->reset(['image']);
    }
}
