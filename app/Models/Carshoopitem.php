<?php

namespace App\Models;

use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Carshoopitem extends Model
{
    use HasFactory;
    use KardexTrait;

    public $timestamps = false;
    protected $fillable = ['pricebuy', 'price', 'igv', 'requireserie', 'producto_id', 'carshoop_id'];


    public function carshoop(): BelongsTo
    {
        return $this->belongsTo(Carshoop::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function kardex(): MorphOne
    {
        return $this->morphOne(Kardex::class, 'kardeable');
    }
}
