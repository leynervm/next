<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Itemserie extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'status', 'serie_id', 'tvitem_id', 'user_id'
    ];

    public $timestamps = false;

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function tvitem(): BelongsTo
    {
        return $this->belongsTo(Tvitem::class);
    }
}
