<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    const ACTIVO = '0';
    const INACTIVO = '1';

    public $timestamps = false;

    protected $fillable = [
        'url', 'link', 'orden', 'start', 'end', 'status'
    ];


    public function scopeActivos($query)
    {
        return $query->where('status', self::ACTIVO);
    }

    public function scopeDisponibles($query)
    {
        return $query->whereDate('start', '<=', now('America/Lima')->format('Y-m-d'))
            ->whereDate('end', '>=', now('America/Lima')->format('Y-m-d'))
            ->orWhereDate('start', '<=', now('America/Lima')->format('Y-m-d'))
            ->whereNull('end');
    }

    public function getImageURL()
    {
        return Storage::url('images/slider/' . $this->url);
    }

    public function isActivo()
    {
        return $this->status == self::ACTIVO;
    }
}
