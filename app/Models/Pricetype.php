<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Producto;

class Pricetype extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['name', 'ganancia', 'decimalrounded', 'default', 'web'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function rangos(): BelongsToMany
    {
        return $this->belongsToMany(Rango::class)
        ->withPivot('id', 'ganancia')->orderBy('ganancia', 'desc');
    }

    public function scopeDefaultPricetype($query)
    {
        return $query->where('default', 1);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class)->withPivot('price');
    }
}
