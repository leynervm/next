<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ValidateStock implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $producto_id, $almacen_id, $cantidad, $mensaje;

    public function __construct($producto_id, $almacen_id, $cantidad = null)
    {
        $this->producto_id = $producto_id;
        $this->almacen_id = $almacen_id;
        $this->cantidad = $cantidad;
        $this->mensaje = 'Stock no disponible en almacén seleccionado.';
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


        if (empty($this->almacen_id)) {
            $this->mensaje = "El campo almacen es obligatorio.";
            return;
        }
        $producto = Producto::with(['unit'])->withCount(['almacens as stock' => function ($query) {
            // if (empty($cart['almacen_id'])) {
            $query->where('almacen_id', $this->almacen_id)->select(DB::raw('COALESCE(SUM(cantidad), 0)'));
            // } else {
            //     $query->select(DB::raw('COALESCE(SUM(cantidad),0)'));
            // }
        }])->find($this->producto_id);
        // $query = $producto->almacens->find($this->almacen_id)->pivot->cantidad;

        $this->mensaje = $this->cantidad > 0
            ? "Cantidad supera al stock disponible [" . decimalOrInteger($producto->stock) . " " . $producto->unit->name . "]."
            : "Stock no disponible en almacén seleccionado [" . decimalOrInteger($producto->stock) . " " . $producto->unit->name . "].";
        $restantes = $this->cantidad > 0 ? $producto->stock - $this->cantidad : $producto->stock;
        return $this->cantidad > 0 ? $restantes >= 0 : $restantes > 0;
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
