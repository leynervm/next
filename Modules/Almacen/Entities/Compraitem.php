<?php

namespace Modules\Almacen\Entities;

use App\Models\Serie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compraitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $guarded = ['created_at', 'updated_at'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class);
    }

    public function compra(): BelongsTo
    {
        return $this->belongsTo(Compra::class);
    }

    public function seriecompras(): HasMany
    {
        return $this->hasMany(Seriecompra::class);
    }

    public function series(): HasMany
    {
        return $this->hasMany(Serie::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
