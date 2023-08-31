<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function direccions(): MorphMany
    {
        return $this->morphMany(Direccion::class, 'direccionable');
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable')->orderBy('name', 'asc');
    }

    public function pricetype(): BelongsTo
    {
        return $this->belongsTo(Pricetype::class);
    }

    public function channelsale(): belongsTo
    {
        return $this->belongsTo(Channelsale::class);
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
