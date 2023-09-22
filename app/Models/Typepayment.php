<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Typepayment extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['name', 'paycuotas', 'default'];

    public function isDefault()
    {
        return $this->default === 1;
    }

    public function isContado()
    {
        return $this->paycuotas === 1;
    }

    public function scopeDefaultTypepayment($query)
    {
        return $query->where('default', 1);
    }
}
