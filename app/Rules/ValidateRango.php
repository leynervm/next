<?php

namespace App\Rules;

use App\Models\Rango;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidateRango implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $desde;
    protected $hasta;
    protected $ignoreId;
    protected $mensaje;

    public function __construct($desde = null, $hasta = null,  $ignoreId = null)
    {
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->ignoreId = $ignoreId;
        $this->mensaje = 'El rango seleccionado ya se encuentra registrado.';
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
        // $query = Rango::whereBetween($this->column, [$value, $value]);

        if ($this->desde === null || $this->hasta === null) {
            $this->mensaje = 'El campoo :attribute ya existe en el rango seleccionado.';
            return true;
        }

        $query = Rango::where(function ($query) use ($value) {
            $query->where('desde', '<=', $this->desde)
                ->where('hasta', '>=', $this->desde)
                ->orWhere('desde', '<=', $this->hasta)
                ->where('hasta', '>=', $this->hasta);
        });

        if (!is_null($this->ignoreId)) {
            $query->where('id', '<>', $this->ignoreId);
        }

        return !$query->exists();
        // return $query->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El campoo :attribute ya existe en el rango seleccionado.';
    }
}
