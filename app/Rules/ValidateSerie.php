<?php

namespace App\Rules;

use App\Models\Serie;
use Illuminate\Contracts\Validation\Rule;

class ValidateSerie implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $almacen_id, $producto_id;

    public function __construct($almacen_id = null, $producto_id = null)
    {
        $this->almacen_id = $almacen_id;
        $this->producto_id = $producto_id;
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
        $query = Serie::whereRaw('UPPER(serie) = ?', mb_strtoupper(trim($value, "UTF-8")))
            ->where('status', 0);
        // ->whereNull('dateout');

        if ($this->almacen_id) {
            $query->where('almacen_id', $this->almacen_id);
        }

        if ($this->producto_id) {
            $query->where('producto_id', $this->almacen_id);
        }

        return $query->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Serie del producto no disponible.';
    }
}
