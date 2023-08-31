<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pricetype extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function rangos(): BelongsToMany
    {
        return $this->belongsToMany(Rango::class)
        ->withPivot('id', 'ganancia', 'user_id')->orderBy('ganancia', 'desc');
    }

    public function scopeDefaultPricetype($query)
    {
        return $query->where('default', 1);
    }
}
