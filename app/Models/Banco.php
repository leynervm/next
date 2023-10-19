<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banco extends Model
{
    use HasFactory;
    use SoftDeletes;

    const ACTIVO = "0";
    const INACTIVO = "1";

    public $timestamps = false;

    protected $fillable = ['name', 'status'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function cuentas(): HasMany
    {
        return $this->hasMany(Cuenta::class);
    }
}
