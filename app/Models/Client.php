<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ventas\Entities\Venta;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    const EMPRESA = 'E';

    public $timestamps = false;

    protected $fillable = ['document', 'name', 'email', 'nacimiento', 'sexo', 'pricetype_id', 'user_id'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = trim(mb_strtolower($value, "UTF-8"));
    }

    public function direccion(): MorphOne
    {
        return $this->morphOne(Direccion::class, 'direccionable')
            ->orderByDesc('default')->orderByDesc('id');
    }

    public function direccions(): MorphMany
    {
        return $this->morphMany(Direccion::class, 'direccionable')
            ->orderByDesc('default')->orderByDesc('id');
    }

    public function ventas(): HasMany
    {
        return $this->hasMany(Venta::class);
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable')->orderBy('default', 'desc');
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable')->orderBy('name', 'asc');
    }

    public function pricetype(): BelongsTo
    {
        return $this->belongsTo(Pricetype::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function contacts()
    // {
    //     return $this->hasMany(Atenciontype::class);
    // }


    // public function bestClient()
    // {
    //     return $this->hasMany(Venta::class)->count('id');
    // }
}
