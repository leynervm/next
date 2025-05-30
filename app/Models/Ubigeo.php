<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'ubigeo_reniec',
        'ubigeo_inei',
        'departamento_inei',
        'departamento',
        'provincia_inei',
        'provincia',
        'distrito',
        'region',
        'superficie',
        'altitud',
        'latitud',
        'longitud'
    ];

    public function scopeSearchByParams($query, $params)
    {
        $query->when(array_key_exists('ubigeo', $params), function ($q) use ($params) {
            $q->where('ubigeo_inei', trim($params['ubigeo']));
        })
            ->when(array_key_exists('distrito', $params), function ($q) use ($params) {
                $q->whereRaw('LOWER(distrito) = ?', [toStrLowercase($params['distrito'])]);
            })
            ->when(array_key_exists('provincia', $params), function ($q) use ($params) {
                $q->whereRaw('LOWER(provincia) = ?', [toStrLowercase($params['provincia'])]);
            });
    }
}
