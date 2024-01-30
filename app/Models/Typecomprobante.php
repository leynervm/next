<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typecomprobante extends Model
{
    use HasFactory;
    use SoftDeletes;
    const SENDSUNAT = "1";
    const DEFAULT = "0";

    public $timestamps = false;
    protected $fillable = ['code', 'descripcion', 'sendsunat'];

    public function isDefault()
    {
        return $this->default === 0;
    }

    public function scopeDefault($query)
    {
        return $query->where('sendsunat', Typecomprobante::DEFAULT);
    }

    public function scopeFacturables($query)
    {
        return $query->where('sendsunat', Typecomprobante::SENDSUNAT);
    }

    public function seriecomprobantes(): HasMany
    {
        return $this->hasMany(Seriecomprobante::class);
    }

    public function motivotraslados(): HasMany
    {
        return $this->hasMany(Motivotraslado::class);
    }

    // public function scopeSucursalTypecomprobantes($query)
    // {
    //     return $query->withWhereHas('seriecomprobantes', function ($query) {
    //         $query->whereHas('sucursals', function ($query) {
    //             $query->where('sucursal_id', auth()->user()->sucursal_id);
    //         });
    //     });
    // }

    // public function scopeDefaultSucursalTypecomprobantes()
    // {
    //     return $this->default()->withWhereHas('seriecomprobantes', function ($query) {
    //         $query->whereHas('sucursals', function ($query) {
    //             $query->where('sucursal_id', auth()->user()->sucursal_id);
    //         });
    //     });
    // }
}
