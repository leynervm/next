<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Carshoopserie extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    public function carshoop(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }
}
