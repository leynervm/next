<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'ubigeo_id', 'direccionable_id', 'direccionable_type'];
    const DEFAULT = '1';

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function direccionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function ubigeo(): BelongsTo
    {
        return $this->belongsTo(Ubigeo::class);
    }

    public function isDefault()
    {
        return $this->default == self::DEFAULT;
    }
}
