<?php

namespace App\Models;

use App\Traits\KardexTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Almacen;
use App\Models\Producto;

class Tvitem extends Model
{
    use HasFactory;
    use SoftDeletes;
    use KardexTrait;

    protected $guarded = ['created_at', 'updated_at'];

    const GRATUITO = '1';
    const PENDING_SERIE = '1';

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
    
    public function isPendingSerie()
    {
        return $this->requireserie == self::PENDING_SERIE;
    }
}
