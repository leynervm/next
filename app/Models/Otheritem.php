<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Otheritem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'date',
        'name',
        'marca',
        'cantidad',
        'pricebuy',
        'price',
        'igv',
        'subtotaligv',
        'subtotal',
        'total',
        'increment',
        'status',
        'gratuito',
        'unit_id',
        'user_id',
        'otheritemable_id',
        'otheritemable_type',
    ];

    const NO_GRATUITO = '0';
    const GRATUITO = '1';


    public function otheritemable(): MorphTo
    {
        return $this->morphTo();
    }

    // public function itemseries()
    // {
    //     return $this->hasMany(Itemserie::class);
    // }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeGratuitos($query)
    {
        return $query->where('gratuito', Self::GRATUITO);
    }
}
