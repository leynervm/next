<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rango extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['desde', 'hasta', 'incremento'];

    public function getDesdeAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    public function getHastaAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }

    public function getIncrementoAttribute($value)
    {
        return number_format($value, 2, '.', '');
    }


    // public function getIncremento($value)
    // {
    //     return formatDecimalOrInteger($value);
    // }

    public function pricetypes(): BelongsToMany
    {
        return $this->belongsToMany(Pricetype::class)
            ->withPivot('id', 'ganancia');
    }
}
