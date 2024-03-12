<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Box extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    const ACTIVO = "0";
    const INACTIVO = "1";

    protected $fillable = ['name', 'apertura', 'status', 'user_id', 'sucursal_id'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function openboxes(): HasMany
    {
        return $this->hasMany(Openbox::class);
    }

    public function scopeDisponibles($query)
    {
        return $query->where('status', self::ACTIVO);
    }

    public function scopeSucursal($query)
    {
        return $query->where('sucursal_id', auth()->user()->sucursal_id);
    }

    public function isUsing()
    {
        return $this->sucursal_id == auth()->user()->sucursal_id;
    }
}
