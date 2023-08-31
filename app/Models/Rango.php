<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rango extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function pricetypes(): BelongsToMany
    {
        return $this->belongsToMany(Pricetype::class)
            ->withPivot('id', 'ganancia', 'user_id')->orderBy('ganancia', 'desc');
    }
}
