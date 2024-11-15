<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Producto;
use App\Models\Subcategory;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    public $timestamps = false;
    protected $fillable = ['name', 'orden', 'slug', 'icon'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function subcategories(): BelongsToMany
    {
        return $this->BelongsToMany(Subcategory::class)
            ->orderBy('orden', 'asc')->orderBy('name', 'asc');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
