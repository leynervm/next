<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        // 'Modules\Facturacion\Entities\Comprobante' => \Modules\Facturacion\Policies\PolicyComprobante::class,
        // 'Modules\Almacen\Entities\Compra' => \Modules\Almacen\Policies\PolicyCompra::class,
        // 'Modules\Ventas\Entities\Venta' => \Modules\Ventas\Policies\PolicyVenta::class,
    ];
}
