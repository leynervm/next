<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Telephone extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['phone', 'telephoneable_id', 'telephoneable_type'];

    public function telephoneable(): MorphTo
    {
        return $this->morphTo();
    }
}
