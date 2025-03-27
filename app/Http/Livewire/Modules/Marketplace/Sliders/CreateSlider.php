<?php

namespace App\Http\Livewire\Modules\Marketplace\Sliders;

use App\Models\Slider;
use App\Rules\ValidateImageBase64;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManagerStatic as ImageIntervention;


class CreateSlider extends Component
{

    public $open = false;

    use AuthorizesRequests;
    use WithFileUploads;

    public $image, $imagemobile;
    public $iddesk, $idmobile;
    public $orden, $link, $start, $end;

    protected function rules()
    {
        return [
            'link' => ['nullable', 'url'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date'],
            'orden' => ['required', 'integer', 'min:0'],
            'image' => [
                'required',
                'string',
                'regex:/^data:image\/(png|jpg|jpeg|webp);base64,([A-Za-z0-9+\/=]+)$/',
                // 'max:5120',
                // 'dimensions:min_width=1920,min_height=560'
                new ValidateImageBase64(1920, 560, ['JPG', 'JPEG', 'PNG', 'WEBP'])
            ],
            'imagemobile' => [
                'required',
                'string',
                'regex:/^data:image\/(png|jpg|jpeg|webp);base64,([A-Za-z0-9+\/=]+)$/',
                new ValidateImageBase64(720, 833, ['JPG', 'JPEG', 'PNG', 'WEBP'])
            ],
        ];
    }

    public function mount()
    {
        $this->iddesk = rand();
        $this->idmobile = rand();
        $this->start = now('America/Lima')->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.modules.marketplace.sliders.create-slider');
    }

    public function updatingOpen()
    {
        if (!$this->open) {
            $this->reset(['image', 'imagemobile', 'link']);
            $this->resetValidation();
            $this->iddesk = rand();
            $this->idmobile = rand();
            $this->start = now('America/Lima')->format('Y-m-d');
        }
    }

    public function save($closemodal = false)
    {
        $this->authorize('admin.marketplace.sliders.create');
        DB::beginTransaction();
        try {
            $this->orden = Slider::exists() ? Slider::max('orden') + 1 : '1';
            $this->validate();

            $urlslider = $this->savepicture('image', 1920, 560, 'desk_');
            $urlslidermobile = $this->savepicture('imagemobile', 720, 833, 'mobile_');

            Slider::create([
                'url' => $urlslider,
                'urlmobile' => $urlslidermobile,
                'link' => $this->link,
                'orden' => $this->orden,
                'start' => $this->start,
                'end' => empty($this->end) ? null : $this->end,
            ]);
            DB::commit();
            $this->dispatchBrowserEvent('created');
            $this->emit('render');
            if ($closemodal) {
                $this->reset();
            } else {
                $this->resetExcept('open');
            }
            $this->iddesk = rand();
            $this->idmobile = rand();
            $this->start = now('America/Lima')->format('Y-m-d');
            $this->resetValidation();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function savepicture($attribute, $width, $height, $responsive)
    {

        $allowedMimes = ['jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
        $imageSlider = $this->{$attribute};
        $imageData = explode(',', $imageSlider)[1] ?? $imageSlider;
        $decodedImage = base64_decode($imageData);
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->buffer($decodedImage);

        if (!in_array($mime, $allowedMimes)) {
            $this->addError('imagen', 'Formato no soportado (solo JPEG, PNG, WebP)');
            return false;
        }

        // list($type, $imageSlider) = explode(';', $imageSlider);
        // list(, $imageSlider) = explode(',', $imageSlider);
        // $imageSlider = base64_decode($imageSlider);

        $compressedSlider = ImageIntervention::make($decodedImage)
            ->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->orientate()->encode('webp', 90);
        // $compressedSlider = ImageIntervention::make($imageSlider)->orientate()->encode('jpg', 30);

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

    public function clearimgdesk()
    {
        $this->reset(['image', 'iddesk']);
        $this->resetValidation();
        $this->image = rand();
    }

    public function clearimgmobile()
    {
        $this->reset(['imagemobile', 'idmobile']);
        $this->resetValidation();
        $this->imagemobile = rand();
    }

    public function updated($propertyName)
    {
        $this->resetValidation();
    }
}
