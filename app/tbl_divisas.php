<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_divisas extends Model
{
    public static function get_list($show_moneda_nacional = false)
    {
        $model = tbl_divisas::query()
        ->where([
            ['activo', '=', true]
        ]);

        if(!$show_moneda_nacional)
            $model->where('divisa_id', '>', 0);

        return $model->get();
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
