<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;

class Seriecomprobante extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['serie', 'code', 'contador', 'typecomprobante_id'];

    public function setSerieAttribute($value)
    {
        $this->attributes['serie'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function typecomprobante(): BelongsTo
    {
        return $this->belongsTo(typecomprobante::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function sucursals(): BelongsToMany
    {
        return $this->belongsToMany(Sucursal::class)->withPivot('default');
    }

    // public function scopeDefaultseriecomprobantes()
    // {
    //     return $this->belongsToMany(Seriecomprobante::class)
    //         ->withPivot('default');
    // }
}
