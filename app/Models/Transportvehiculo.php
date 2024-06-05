<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transportvehiculo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['placa', 'tarjeta', 'autorizacion', 'principal', 'guia_id'];

    const PRINCIPAL = '1';
    const SECUNDARIO = '0';

    public function setTarjetaAttribute($value)
    {
        $this->attributes['tarjeta'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setPlacaAttribute($value)
    {
        $this->attributes['placa'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class);
    }

    public function scopePrincipal($query)
    {
        $query->where('principal', $this::PRINCIPAL);
    }

    public function scopeSecundario($query)
    {
        $query->where('principal', $this::SECUNDARIO);
    }

    public function isPrincipal()
    {
        return $this->principal == $this::PRINCIPAL;
    }

    public function isSecundario()
    {
        return $this->principal == $this::SECUNDARIO;
    }
}
