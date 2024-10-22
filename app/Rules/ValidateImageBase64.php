<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateImageBase64 implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $width, $height, $formats = [], $mensaje = '';

    public function __construct($width, $height, $formats = [])
    {
        $this->width = $width;
        $this->height = $height;
        $this->formats = $formats;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (empty($value)) {
            $this->mensaje = "El :attribute debe ser obligatorio.";
            return false;
        }

        $imageSlider = $value;
        list($type, $imageSlider) = explode(';', $imageSlider);
        list(, $imageSlider) = explode(',', $imageSlider);
        $imageSlider = base64_decode($imageSlider);
        $image = @imagecreatefromstring($imageSlider);

        if ($image === false) {
            $this->mensaje = "El formato del :attribute debe ser " . implode(', ', $this->formats);
            return false;
        }

        if (!$image) {
            $this->mensaje = "El formato del :attribute debe ser " . implode(', ', $this->formats);
            return false;
        }

        if (imagesx($image) < $this->width || imagesy($image) < $this->height) {
            $this->mensaje = "La :attribute debe tener dimensiones de " . $this->width . 'x' . $this->height . " píxeles.";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->mensaje;
    }
}
