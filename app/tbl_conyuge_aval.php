<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_conyuge_aval extends Model
{
    protected $table = 'tbl_conyuge_aval';
    public $timestamps = false;
    protected $primaryKey = 'conyuge_aval_id';
    protected $hidden = [
        'conyuge_aval_id', 'aval_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'conyuge_aval_id', 'aval_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento',
        'telefono_movil', 'lugar_trabajo', 'puesto', 'jefe'
    ];

    public static function create(tbl_conyuge_aval $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_conyuge_aval $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_id($id)
    {
        $model = tbl_conyuge_aval::where([
            ['conyuge_aval_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_aval_id($aval_id)
    {
        $model = tbl_conyuge_aval::where([
            ['aval_id', '=', $aval_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
