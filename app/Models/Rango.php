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

    public function pricetypes(): BelongsToMany
    {
        return $this->belongsToMany(Pricetype::class)
            ->withPivot('id', 'ganancia')->orderBy('ganancia', 'desc');
    }
}
