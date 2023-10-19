<?php

namespace Modules\Almacen\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seriecompra extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

}
