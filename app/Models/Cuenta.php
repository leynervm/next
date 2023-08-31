<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cuenta extends Model
{
    use HasFactory;
    use SoftDeletes;

    const ACTIVO = "0";
    const INACTIVO = "1";

    protected $guarded = ['created_at', 'updated_at'];

    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = trim(mb_strtoupper($value, "UTF-8"));
    }


    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class);
    }

    public function methodpayments(): BelongsToMany
    {
        return $this->belongsToMany(Methodpayment::class);
    }
}
