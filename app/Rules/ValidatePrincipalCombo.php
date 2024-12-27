<?php

namespace App\Rules;

use App\Models\Combo;
use App\Models\Promocion;
use Illuminate\Contracts\Validation\Rule;

class ValidatePrincipalCombo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $producto_id, $type, $mensaje;

    public function __construct($producto_id, $type)
    {
        $this->type = $type;
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
        // if ($this->type == Promocion::DESCUENTO) {
        //     $promocions = Promocion::where('type', Promocion::DESCUENTO)->activos()
        //         ->where('producto_id', $this->producto_id);
        // } else {
        //     $promocions = Promocion::whereIn('type', [Promocion::COMBO, Promocion::REMATE])->activos()
        //         ->where('producto_id', $this->producto_id);
        // }
        if ($this->type != Promocion::COMBO) {
            $promocions = Promocion::where('status', Promocion::ACTIVO)
                ->where('producto_id', $this->producto_id)
                ->where('type', '<>', Promocion::COMBO)
                ->availables()->disponibles()->exists();
            if ($promocions) {
                $this->mensaje = 'Producto ya tiene promociones activas.';
                return false;
            }
        }

        // $itemcombos = Promocion::where('status', Promocion::ACTIVO)
        //     ->disponibles()->withWhereHas('itempromos', function ($query) {
        //         $query->where('producto_id', $this->producto_id);
        //     })->exists();

        // if ($itemcombos) {
        //     $this->mensaje = 'Producto ya se encuentra dentro de una promoción activa.';
        //     return false;
        // }
        return true;

        // return $promocions || $itemcombos === false;
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
