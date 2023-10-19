<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedortype extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

}
