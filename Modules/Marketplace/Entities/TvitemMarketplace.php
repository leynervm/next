<?php

namespace Modules\Marketplace\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TvitemMarketplace extends Model
{
    use HasFactory;

    protected $table = 'tvitems';

    protected $guarded = ['created_at', 'updated_at'];

    protected static function newFactory()
    {
        return \Modules\Marketplace\Database\factories\TvitemMarketplaceFactory::new();
    }

    public function getGratuitoAttribute($value)
    {
        return (int) $value;
    }

    public function tvitemable(): MorphTo
    {
        return $this->morphTo();
    }
}
