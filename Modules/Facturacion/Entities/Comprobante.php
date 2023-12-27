<?php

namespace Modules\Facturacion\Entities;

use App\Models\Client;
use App\Models\Cuota;
use App\Models\Empresa;
use App\Models\Guia;
use App\Models\Moneda;
use App\Models\Seriecomprobante;
use App\Models\Sucursal;
use App\Models\Tribute;
use App\Models\Typepayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comprobante extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['created_at', 'updated_at'];

    public function setDireccionAttribute($value)
    {
        $this->attributes['direccion'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setLeyendaAttribute($value)
    {
        $this->attributes['leyenda'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function facturable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function seriecomprobante(): BelongsTo
    {
        return $this->belongsTo(Seriecomprobante::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function facturableitems(): HasMany
    {
        return $this->hasMany(Facturableitem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typepayment()
    {
        return $this->belongsTo(Typepayment::class);
    }

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }

    public function guias(): HasMany
    {
        return $this->hasMany(Guia::class);
    }

    public function cuotas(): MorphMany
    {
        return $this->morphMany(Cuota::class, 'cuotable');
    }

    public function scopeWhereDateBetween($query, $fieldName, $date, $dateto)
    {
        return $query->whereDate($fieldName, '>=', $date)->whereDate($fieldName, '<=', $dateto);
    }
}
