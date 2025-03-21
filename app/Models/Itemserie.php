<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Itemserie extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'serie_id', 'user_id', 'seriable_id', 'seriable_type'];
    public $timestamps = false;

    public function seriable(): MorphTo
    {
        return $this->morphTo();
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // public function tvitem(): BelongsTo
    // {
    //     return $this->belongsTo(Tvitem::class);
    // }



    public function isTvitem()
    {
        return $this->seriable_type == Tvitem::class;
    }

    public function isCarshoopitem()
    {
        return $this->seriable_type == Carshoopitem::class;
    }
}
