<?php

namespace Modules\Marketplace\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaccion extends Model
{
    use HasFactory;
    public $timestamps = false;
    const AUTORIZADO = '000';

    protected $fillable = [
        'date', 'amount', 'currency', 'eci_description', 'action_description',
        'transaction_id', 'card', 'card_type', 'status', 'action_code',
        'brand', 'email', 'order_id', 'user_id'
    ];


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeAutorizados($query)
    {
        return $query->where('action_code', self::AUTORIZADO);
    }
}
