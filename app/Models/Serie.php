<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Almacen\Entities\Almacen;
use Modules\Almacen\Entities\Producto;

class Serie extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function setSerieAttribute($value)
    {
        $this->attributes['serie'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function almacen():BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function producto():BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function compra():BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
