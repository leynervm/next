<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transportdriver extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['document', 'name', 'lastname', 'licencia', 'principal', 'guia_id'];

    const PRINCIPAL = '1';
    const SECUNDARIO = '0';
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function setLicenciaAttribute($value)
    {
        $this->attributes['licencia'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class);
    }

    public function scopePrincipal($query)
    {
        $query->where('principal', $this::PRINCIPAL);
    }

    public function isPrincipal()
    {
        return $this->principal == $this::PRINCIPAL;
    }

}

