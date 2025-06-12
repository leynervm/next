<?php

namespace Modules\Soporte\Entities;

use App\Models\Areawork;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Direccion;
use App\Models\Sucursal;
use App\Models\Telephone;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Soporte\Entities\Atencion;
use Modules\Soporte\Entities\Condition;
use Modules\Soporte\Entities\Entorno;
use Modules\Soporte\Entities\Priority;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = false;
    protected $fillable = [
        'date',
        'seriecompleta',
        'dateasigned',
        'detalle',
        'total',
        'atencion_id',
        'condition_id',
        'centerservice_id',
        'priority_id',
        'areawork_id',
        'entorno_id',
        'estate_id',
        'client_id',
        'userasigned_id',
        'user_id',
        'sucursal_id',
    ];

    const SERIE = 'OT';

    public function setDetalleAttribute($value)
    {
        $this->attributes['detalle'] = trim(mb_strtoupper($value, "UTF-8"));
    }

    protected static function booted()
    {
        static::created(function ($ticket) {
            $serie =  $ticket->sucursal_id < 100 ? Self::SERIE . str_pad($ticket->sucursal_id, 2, '0', STR_PAD_LEFT) : Self::SERIE . $ticket->sucursal_id;
            $seriecompleta = "$serie-$ticket->id"; //TK01-1
            $ticket->estate_id = Estate::default()->first()->id;
            $ticket->seriecompleta = $seriecompleta;
            // $ticket->serie = Self::SERIE . '-' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT);
            $ticket->save();
        });
    }

    public function generarCodigoTicket($numero)
    {
        if ($numero < 100) {

            $numeroFormateado = str_pad($numero, 2, '0', STR_PAD_LEFT);
        } else {
            $numeroFormateado = $numero;
        }

        return 'TK' . $numeroFormateado;
    }


    public function equipo(): HasOne
    {
        return $this->hasOne(Equipo::class);
    }

    public function direccion(): MorphOne
    {
        return $this->morphOne(Direccion::class, 'direccionable');
    }

    public function telephones(): MorphMany
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function contact(): MorphOne
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function atencion(): BelongsTo
    {
        return $this->belongsTo(Atencion::class);
    }

    public function areawork(): BelongsTo
    {
        return $this->belongsTo(Areawork::class)->withTrashed();
    }

    public function entorno(): BelongsTo
    {
        return $this->belongsTo(Entorno::class)->withTrashed();
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class)->withTrashed();
    }

    public function userasigned(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userasigned_id')->withTrashed();
    }

    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class)->withTrashed();
    }

    public function procesos(): HasMany
    {
        return $this->hasMany(Proceso::class);
    }
}
