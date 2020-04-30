<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_estados extends Model
{
    public static function get_list()
    {
        $model = tbl_estados::where([
            ['activo', '=', true]
        ])->orderby('estado')
            ->get();
        return $model;
    }
}
