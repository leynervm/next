<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Especificacion extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'caracteristica_id'];
    public $timestamps = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function caracteristica(): BelongsTo
    {
        return $this->belongsTo(Caracteristica::class);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class);
    }

    // public function caracteristica(): BelongsTo
    // {
    //     return $this->belongsTo(Caracteristica::class);
    // }
}
