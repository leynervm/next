<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Telephone extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function telephoneable(): MorphTo
    {
        return $this->morphTo();
    }
}
