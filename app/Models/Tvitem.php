<?php

namespace App\Models;

use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tvitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use KardexTrait;

    protected $guarded = ['created_at', 'updated_at'];

    const NO_GRATUITO = '0';
    const GRATUITO = '1';

    protected static function newFactory()
    {
        return \Modules\Marketplace\Database\factories\TvitemMarketplaceFactory::new();
    }

    public function tvitemable(): MorphTo
    {
        return $this->morphTo();
    }

    public function itemseries()
    {
        return $this->hasMany(Itemserie::class);
    }

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class);
    }
}
