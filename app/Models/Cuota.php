<?php

namespace App\Models;

use App\Models\Cajamovimiento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Cuota extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function cuotable(): MorphTo
    {
        return $this->morphTo();
    }

    public function cajamovimiento(): MorphOne
    {
        return $this->morphOne(Cajamovimiento::class, 'cajamovimientable');
    }

    public function userpay(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userpayment_id');
    }
}
