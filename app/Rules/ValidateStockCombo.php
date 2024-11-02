<?php

namespace App\Rules;

use App\Models\Producto;
use Illuminate\Contracts\Validation\Rule;

class ValidateStockCombo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $principal_id, $limit, $mensaje;

    public function __construct($principal_id, $limit = 0)
    {
        $this->principal_id = $principal_id;
        $this->limit = $limit;
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

        $comboCollect = getCombo();
        $comboitems = collect($comboCollect->get('comboitems') ?? []);

        if (count($comboitems) > 0) {
            foreach ($comboitems as $item) {
                $stocksecundario = decimalOrInteger(Producto::find($item->producto_id)->almacens()->sum('cantidad'));
                if ($this->limit > $stocksecundario) {
                    $this->mensaje = 'Stock del producto ' . $item->name . ' no disponible.';
                    return false;
                }
            }
        }


        $filtered = $comboitems->filter(function ($item) {
            return $item->producto_id == $this->principal_id;
        });

        if ($filtered->count() > 0) {
            $this->mensaje =  'Producto principal no disponible para los items secundarios.';
            return false;
        }


        $stockprincipal = decimalOrInteger(Producto::find($this->principal_id)->almacens()->sum('cantidad'));
        if ($this->limit > 0) {
            if ($this->limit > $stockprincipal) {
                $this->mensaje = 'Stock del producto principal no disponible.';
                return false;
            }
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
