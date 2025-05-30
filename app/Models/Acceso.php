<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{
    use HasFactory;

    const SUSPENDIDO = '0';
    const ACTIVO = '1';
    const LIMIT_SUCURSALS = '2';

    public $timestamps = false;

    protected $fillable = [
        'access',
        'limitsucursals',
        'validatemail',
        'dominio',
        'descripcion'
    ];

    public function access()
    {
        return $this->access == self::ACTIVO;
    }

    public function suspended()
    {
        return $this->access != self::ACTIVO;
    }

    public function verifyemail()
    {
        return $this->validatemail == self::ACTIVO;
    }

    public function unlimit()
    {
        return $this->limitsucursals == null;
    }
}
