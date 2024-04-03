<?php

namespace App\Models;

use App\Traits\ValidatePermissionModules;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{

    use ValidatePermissionModules;

    

}
