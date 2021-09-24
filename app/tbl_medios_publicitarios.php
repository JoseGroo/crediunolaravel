<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_medios_publicitarios extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'medio_publicitario_id';
    protected $hidden = ['medio_publicitario_id', 'activo', 'creado_por', 'fecha_creacion'];


    protected $fillable = [
        'medio_publicitario_id', 'medio_publicitario'
    ];

    public static function create(tbl_medios_publicitarios $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_medios_publicitarios $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function check_if_exists($medio_publicitario, $id)
    {
        $model = \DB::table("tbl_medios_publicitarios")
            ->where([
                ['medio_publicitario_id', '!=', $id],
                ['activo', '=', true],
                ['medio_publicitario', '=', $medio_publicitario]
            ])
            ->get()->count();

        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_medios_publicitarios::where([
            ['medio_publicitario_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list()
    {
        $model = tbl_medios_publicitarios::where([
            ['activo', '=', true]
        ])->orderby('medio_publicitario_id')
            ->get();
        return $model;
    }

    public static function get_pagination($medio_publicitario, $perPage)
    {

        $model = tbl_medios_publicitarios::where([
            ['medio_publicitario', 'LIKE', '%'.$medio_publicitario.'%'],
            ['activo', '=', true],
        ])
            ->orderBy('medio_publicitario', 'asc')
            ->paginate($perPage);
        return $model;
    }
}
