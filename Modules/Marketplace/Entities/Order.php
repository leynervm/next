<?php

namespace Modules\Marketplace\Entities;

use App\Enums\MethodPaymentOnlineEnum;
use App\Enums\StatusPayWebEnum;
use App\Models\Client;
use App\Models\Direccion;
use App\Models\Image;
use App\Models\Moneda;
use App\Models\Tvitem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Facturacion\Entities\Comprobante;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'date', 'seriecompleta', 'exonerado', 'gravado',  'igv',
        'subtotal', 'total', 'tipocambio', 'receiverinfo', 'methodpay', 'direccion_id',
        'status', 'shipmenttype_id', 'moneda_id', 'client_id', 'user_id',
    ];

    const EQUAL_RECEIVER = '0';
    const OTHER_RECEIVER = '1';

    protected $casts = [
        'receiverinfo' => 'array',
        'methodpay' => MethodPaymentOnlineEnum::class,
        'status' => StatusPayWebEnum::class
    ];

    protected static function newFactory()
    {
        return \Modules\Marketplace\Database\factories\OrderFactory::new();
    }


    public function shipmenttype(): BelongsTo
    {
        return $this->belongsTo(Shipmenttype::class)->withTrashed();;
    }

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class)->withTrashed();;
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();;
    }

    public function entrega(): HasOne
    {
        return $this->hasOne(Entrega::class);
    }

    public function direccion(): BelongsTo
    {
        return $this->belongsTo(Direccion::class)->withTrashed();;
    }

    public function transaccions(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(Tracking::class);
    }

    public function comprobante(): MorphOne
    {
        return $this->morphOne(Comprobante::class, 'facturable');
    }

    public function tvitems(): MorphMany
    {
        return $this->morphMany(Tvitem::class, 'tvitemable')->orderBy('id', 'asc');
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function isPendiente()
    {
        return $this->status->value == StatusPayWebEnum::PENDIENTE->value;
    }

    public function isPagado()
    {
        return $this->status->value == StatusPayWebEnum::CONFIRMAR_PAGO->value;
    }

    public function isPagoconfirmado()
    {
        return $this->status->value == StatusPayWebEnum::PAGO_CONFIRMADO->value;
    }

    public function isDeposito()
    {
        return $this->methodpay == MethodPaymentOnlineEnum::DEPOSITO_BANCARIO;
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }

    public function scopeEntregados($query)
    {
        return $query->whereHas('trackings', function ($query) {
            $query->whereHas('trackingstate', function ($query) {
                $query->where('finish', Trackingstate::FINISH);
            });
        });
    }
}
