<?php

namespace Modules\Soporte\Entities;

use App\Models\Contact;
use App\Models\Marca;
use App\Models\Telephone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Centerservice extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }
}
