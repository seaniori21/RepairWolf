<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Meta {
    public static function permissionGroups($guard = 'web')
    {
        $permissons = Permission::where('guard_name', $guard)->get();
        $output = [];

        foreach ($permissons as $key => $value) {
            $output[$value->group_name][] = $value->name;
        }

        return $output;
    }
}