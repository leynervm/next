<?php

namespace App\Models;

use App\Traits\CajamovimientoTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Employerpayment extends Model
{
    use HasFactory;
    use CajamovimientoTrait;


    public $timestamps = false;
    protected $fillable = [
        'month', 'adelantos', 'descuentos', 'bonus', 'amount', 'employer_id'
    ];


    public function cajamovimientos(): MorphOne
    {
        return $this->morphOne(Cajamovimiento::class, 'cajamovimientable');
    }

    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }
}
