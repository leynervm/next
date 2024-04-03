<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;
    public $timestamps = false;

    const DEFAULT = '1';

    protected $fillable = ['url', 'default', 'imageable_id', 'imageable_type'];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }
}
