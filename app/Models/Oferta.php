<?php

namespace App\Models;

use App\Models\Almacen;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oferta extends Model
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfertasDisponibles($query)
    {
        return $query->whereDate('datestart', '<=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->whereDate('dateexpire', '>=', Carbon::now('America/Lima')->format('Y-m-d'))
            ->where('status', 0)->orderBy('datestart', 'asc')->orderBy('status', 'asc')
            ->orderBy('id', 'asc');
    }

    public function isDisponible()
    {
        return $this->status === 0;
    }

}
