<?php

namespace App\Http\Livewire\Modules\Marketplace\Sliders;

use App\Helpers\FormatoPersonalizado;
use App\Models\Slider;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as ImageIntervention;


class CreateSlider extends Component
{

    public $open = false;

    use AuthorizesRequests;
    use WithFileUploads;

    public $image, $extencionimage;
    public $identificador;
    public $orden, $link, $start, $end;

    protected function rules()
    {
        return [
            'link' => ['nullable', 'url'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date'],
            'orden' => ['required', 'integer', 'min:0'],
            'image' => [
                'required', 'file', 'mimes:jpeg,png,gif', 'max:5120', 'dimensions:min_width=1920,min_height=560'
            ]
        ];
    }

    public function mount()
    {
        $this->identificador = rand();
    }

    public function render()
    {
        return view('livewire.modules.marketplace.sliders.create-slider');
    }

    public function updatingOpen()
    {
        if (!$this->open) {
            $this->reset(['image', 'link']);
            $this->resetValidation();
            $this->start = now('America/Lima')->format('Y-m-d');
        }
    }

    public function save()
    {

        $this->authorize('admin.marketplace.sliders.create');

        $this->orden = Slider::exists() ? Slider::max('orden') + 1 : '1';
        $this->validate();

        if (!Storage::directoryExists('images/slider/')) {
            Storage::makeDirectory('images/slider/');
        }

        $compressedImage = ImageIntervention::make($this->image->getRealPath())
            ->resize(1920, 560, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->orientate()->encode('jpg', 30);

        $urlImage = uniqid('slider_') . '.' . $this->image->getClientOriginalExtension();
        $compressedImage->save(public_path('storage/images/slider/' . $urlImage));

        if ($compressedImage->filesize() > 2097152) { //2MB
            $compressedImage->destroy();
            $compressedImage->delete();
            $this->addError('image', 'El campo imagen no debe ser mayor que 2MB.');
            return false;
        }

        Slider::create([
            'url' => $urlImage,
            'link' => $this->link,
            'orden' => $this->orden,
            'start' => $this->start,
            'end' => empty($this->end) ? null : $this->end,
        ]);

        $this->dispatchBrowserEvent('created');
        $this->emit('render');
        $this->reset();
        $this->resetValidation();
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
