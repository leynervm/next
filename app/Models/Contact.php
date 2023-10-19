<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['document', 'name', 'contactable_id', 'contactable_type'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function telephone(): MorphOne
    {
        return $this->morphOne(Telephone::class, 'telephoneable');
    }
}
