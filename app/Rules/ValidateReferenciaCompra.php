<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\Almacen\Entities\Compra;

class ValidateReferenciaCompra implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $proveedor_id, $sucursal_id, $ignore_id;

    public function __construct($proveedor_id, $sucursal_id, $ignore_id = null)
    {
        $this->proveedor_id = $proveedor_id;
        $this->sucursal_id = $sucursal_id;
        $this->ignore_id = $ignore_id;
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
        $query = Compra::whereRaw('UPPER(referencia) = ?', [mb_strtoupper(trim($value), "UTF-8")])
            ->where('proveedor_id', $this->proveedor_id);

        if ($this->ignore_id) {
            $query->where('id', '<>', $this->ignore_id);
        }

        return $query->exists() === false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Referencia de compra existente en proveedor seleccionado.';
    }
}
