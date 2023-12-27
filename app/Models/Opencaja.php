<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Opencaja extends Model
{
    use HasFactory;
    use SoftDeletes;

    const ACTIVO = "0";
    const INACTIVO = "1";

    protected $guarded = ['created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class)->withTrashed();
    }

    public function cajamovimientos(): HasMany
    {
        return $this->hasMany(Cajamovimiento::class);
    }

    public function scopeCajasAbiertas($query)
    {
        return $query->whereNull('expiredate');
    }

    public function scopeCajasUser($query)
    {
        return $query->where('user_id', auth()->user()->id)
            ->orderBy('startdate', 'desc');
    }

    // public function scopeCjSucursal($query)
    // {
    //     return $query->withWhereHas('seriecomprobantes', function ($query) {
    //         $query->whereHas('sucursals', function ($query) {
    //             $query->whereIn('sucursal_id', auth()->user()->sucursalDefault()
    //                 ->select('sucursals.id')->pluck('sucursals.id'));
    //         });
    //     });
    // }

    // public function scopeCajasSucursal($query)
    // {
    //     return $query->where('sucursal_id',  auth()->user()
    //         ->sucursalDefault()->select('sucursals.id')->pluck('sucursals.id'));
    // }

    public function isUsing()
    {
        return $this->user_id == auth()->user()->id;
    }
}
