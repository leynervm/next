<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

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

    public function getImageURL()
    {
        return pathURLProductImage($this->url);
    }

    public function getLogoURL()
    {
        return Storage::url('images/marcas/' . $this->url);
    }

    public function getMarcaURL()
    {
        return Storage::url('images/marcas/' . $this->url);
    }

    public function getLogoEmpresa()
    {
        return Storage::url('images/company/' . $this->url);
    }
}
