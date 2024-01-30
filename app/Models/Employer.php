<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'document', 'name', 'nacimiento', 'telefono', 'sexo',
        'sueldo', 'horaingreso', 'horasalida', 'areawork_id', 'sucursal_id'
    ];
    public $timestamps = false;


    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function areawork(): BelongsTo
    {
        return $this->belongsTo(Areawork::class);
    }


    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }
}
