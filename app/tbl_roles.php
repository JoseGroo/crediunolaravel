<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_roles extends Model
{
    public static function get_list()
    {
        $model = tbl_roles::where([
            ['activo', '=', true]
        ])->orderby('rol_id')
            ->get();
        return $model;
    }

    public static function get_by_name($rol)
    {
        $model = tbl_roles::where([
            ['activo', '=', true],
            ['rol', '=', $rol]
        ])->first();
        return $model;
    }
}
