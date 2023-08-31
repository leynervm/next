<?php

namespace Modules\Ventas\Entities;

use App\Models\Cajamovimiento;
use App\Models\Moneda;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuota extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at'];

    public function cajamovimiento()
    {
        return $this->belongsTo(Cajamovimiento::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function userpay()
    {
        return $this->belongsTo(User::class, 'userpayment_id');
    }

}
