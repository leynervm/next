<?php

namespace Modules\Facturacion\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturableitem extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];
}
