<?php

namespace Modules\Marketplace\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prueba extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        // return \Modules\Marketplace\Database\factories\PruebaFactory::new();
    }
}
