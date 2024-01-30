<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itemcombo extends Model
{
    use HasFactory;
    public $timestamps = false;

    const SIN_DESCUENTO = '0';
    const DESCUENTO = '1';
    const GRATIS = '2';
    
    

    protected $fillable = ['descuento', 'producto_id', 'typecombo', 'combo_id'];

    public function combo(): BelongsTo
    {
        return $this->belongsTo(Combo::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
