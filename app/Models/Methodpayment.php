<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Methodpayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    const DEFAULT = '1';

    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeDefaultMethodpayment($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function cuentas(): BelongsToMany
    {
        return $this->belongsToMany(Cuenta::class);
    }

    public function cajamovimientos()
    {
        return $this->hasMany(Cajamovimiento::class);
    }
}
