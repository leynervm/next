<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ObtenerImage
{

    public function getImageURL()
    {
        $exists =  $this->images()->exists();
        if (!$exists) {
            return null;
        }

        if ($this->images()->default()->exists()) {
            return asset('storage/productos/' . $this->images()->default()->first()->url);
        } else {
            return  asset('storage/productos/' . $this->images->first()->url);
        }
    }

    public function getSecondImageURL()
    {
        if ($this->images()->count() > 1) {
            return Storage::url('productos/' . $this->images()
                ->orderBy('default', 'desc')->skip(1)->first()->url);
        } else {
            return null;
        }
    }
}
