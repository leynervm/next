<?php

namespace App\Http\Livewire\Modules\Marketplace\Sliders;

use App\Models\Slider;
use App\Rules\ValidateImageBase64;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Intervention\Image\ImageManagerStatic as ImageIntervention;
use PhpParser\Node\Expr\Empty_;

use function PHPUnit\Framework\isEmpty;

class ShowSliders extends Component
{

    use WithPagination, WithFileUploads, AuthorizesRequests;

    public $open = false;
    public $slider, $image, $id_image, $extencionimage, $imagemobile, $extencionimagemobile;

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'slider.link' => ['nullable', 'url'],
            'slider.start' => ['required', 'date'],
            'slider.end' => ['nullable', 'date'],
            'slider.url' => ['required'],
            'image' => [
                'nullable',
                Rule::requiredIf(empty($this->slider->url)),
                'string',
                'regex:/^data:image\/(png|jpg|jpeg);base64,([A-Za-z0-9+\/=]+)$/',
                new ValidateImageBase64(1920, 560, ['JPG', 'JPEG', 'PNG', 'WEBP'])
            ],
            'imagemobile' => [
                'nullable',
                Rule::requiredIf(empty($this->slider->urlmobile)),
                'string',
                'regex:/^data:image\/(png|jpg|jpeg);base64,([A-Za-z0-9+\/=]+)$/',
                new ValidateImageBase64(720, 833, ['JPG', 'JPEG', 'PNG', 'WEBP'])
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
            if (Storage::exists('images/slider/' . $this->slider->url)) {
                Storage::delete('images/slider/' . $this->slider->url);
            }
            $urlslider = $this->savepicture('image', 1920, 560, $this->extencionimage, 'desk_');
            $this->slider->url = $urlslider;
        }

        if ($this->imagemobile) {
            if (Storage::exists('images/slider/' . $this->slider->urlmobile)) {
                Storage::delete('images/slider/' . $this->slider->urlmobile);
            }
            $urlslidermobile = $this->savepicture('imagemobile', 720, 833, $this->extencionimagemobile, 'mobile_');
            $this->slider->urlmobile = $urlslidermobile;
        }

        $this->slider->save();
        $this->resetValidation();
        $this->resetExcept(['slider']);
        $this->dispatchBrowserEvent('updated');
    }

    public function delete(Slider $slider)
    {
        $this->authorize('admin.marketplace.sliders.delete');
        if (Storage::exists('images/slider/' . $slider->url)) {
            Storage::delete('images/slider/' . $slider->url);
        }
        if (Storage::exists('images/slider/' . $slider->urlmobile)) {
            Storage::delete('images/slider/' . $slider->urlmobile);
        }
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

    public function updated($propertyName)
    {
        $this->resetValidation();
    }

    public function savepicture($attribute, $width, $height, $extencionimage, $responsive)
    {

        $imageSlider = $this->{$attribute};
        list($type, $imageSlider) = explode(';', $imageSlider);
        list(, $imageSlider) = explode(',', $imageSlider);
        $imageSlider = base64_decode($imageSlider);
        // $compressedSlider = ImageIntervention::make($imageSlider)->orientate()->encode('jpg', 30);
        $compressedSlider = ImageIntervention::make($imageSlider)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->orientate()->encode('webp', 90);

        if ($compressedSlider->width() < $width || $compressedSlider->height() < $height) {
            $this->addError($attribute, "La :imagen debe tener dimensiones de " . $width . 'x' . $height . " píxeles.");
            return false;
        }

        if ($compressedSlider->filesize() > 1048576) { //1MB
            $compressedSlider->destroy();
            $this->addError($attribute, 'La imagen excede el tamaño máximo permitido.');
            return false;
        }

        if (!Storage::directoryExists('images/slider/')) {
            Storage::makeDirectory('images/slider/');
        }

        $urlslider = uniqid('slider_' . $responsive) . '.webp';
        $compressedSlider->save(public_path('storage/images/slider/' . $urlslider));
        return $urlslider;
    }
}
