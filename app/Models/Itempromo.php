<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itempromo extends Model
{
    use HasFactory;

    public $timestamps = false;

    const SIN_DESCUENTO = '0';
    const NORMAL = '0';
    const DESCUENTO = '1';
    const GRATIS = '2';
    const LIQUIDACION = '3';

    protected $fillable = ['titulo', 'descuento', 'producto_id', 'typecombo', 'combo_id'];

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function isSinDescuento()
    {
        return $this->typecombo == self::SIN_DESCUENTO;
    }

    public function isPrecionormal()
    {
        return $this->typecombo == self::NORMAL;
    }

    public function isDescuento()
    {
        return $this->typecombo == self::DESCUENTO;
    }

    public function isGratuito()
    {
        return $this->typecombo == self::GRATIS;
    }

    public function isLiquidacion()
    {
        return $this->typecombo == self::LIQUIDACION;
    }
}
