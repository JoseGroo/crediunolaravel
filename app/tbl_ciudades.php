<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_ciudades extends Model
{
    public static function get_list()
    {
        $model = tbl_ciudades::where([
            ['activo', '=', true]
        ])->orderby('ciudad')
            ->get();
        return $model;
    }

    public static function get_list_by_estado_id($estado_id)
    {
        $model = tbl_ciudades::where([
            ['estado_id', '=', $estado_id],
            ['activo', '=', true]
        ])->orderby('ciudad')
            ->get();
        return $model;
    }
}
