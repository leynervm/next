<?php

namespace Modules\Marketplace\Entities;

use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrega extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date', 'order_id', 'sucursal_id',
    ];

    // protected static function newFactory()
    // {
    //     return \Modules\Marketplace\Database\factories\EntregaFactory::new();
    // }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
