<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usersucursal extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['default', 'almacen_id', 'sucursal_id', 'user_id'];
}
