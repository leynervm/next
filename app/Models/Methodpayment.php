<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Methodpayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = ['name', 'type', 'default'];

    const DEFAULT = '1';

    const EFECTIVO = '0';
    const TRANSFERENCIA = '1';


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    public function scopeDefault($query)
    {
        return $query->where('default', self::DEFAULT);
    }

    public function scopeEfectivo($query)
    {
        return $query->where('type', self::EFECTIVO);
    }

    public function scopeTransferencia($query)
    {
        return $query->where('type', self::TRANSFERENCIA);
    }

    public function cajamovimientos()
    {
        return $this->hasMany(Cajamovimiento::class);
    }
}
