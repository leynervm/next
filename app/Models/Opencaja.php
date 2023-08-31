<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Opencaja extends Model
{
    use HasFactory;
    use SoftDeletes;

    const ACTIVO = "0";
    const INACTIVO = "1";

    protected $guarded = ['created_at', 'updated_at'];

    public function scopeCajasAbiertas($query)
    {
        return $query->where('status', 0);
    }

    public function scopeCajasUser($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }
}
