<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Carshoopserie extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['date', 'serie_id', 'carshoop_id'];

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }
}
