<?php

namespace App\Traits;

use Nwidart\Modules\Facades\Module;

trait ValidatePermissionModules
{

    public function scopeModulesActivePermission($query)
    {
        if (Module::isDisabled('Almacen')) {
            $query->whereNot('module', 'Almacén');
        }

        if (Module::isDisabled('Ventas')) {
            $query->whereNot('module', 'Ventas');
        }

        if (Module::isDisabled('Employer')) {
            $query->whereNot('module', 'Recursos humanos');
        }

        if (Module::isDisabled('Facturacion')) {
            $query->whereNot('module', 'Facturación');
        }

        if (Module::isDisabled('Internet')) {
            $query->whereNot('module', 'Internet');
        }

        if (Module::isDisabled('Soporte')) {
            $query->whereNot('module', 'Soporte');
        }

        if (Module::isDisabled('Marketplace')) {
            $query->whereNot('module', 'Marketplace');
        }

        if (Module::isDisabled('Negocios')) {
            $query->whereNot('module', 'Negocios');
        }
    }
}
