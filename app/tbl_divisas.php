<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_divisas extends Model
{
    public static function get_list()
    {
        $model = tbl_divisas::where([
            ['activo', '=', true]
        ])->get();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_divisas::where([
            ['divisa_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
