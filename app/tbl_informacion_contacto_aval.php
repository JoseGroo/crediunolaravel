<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_informacion_contacto_aval extends Model
{
    protected $table = "tbl_informacion_contacto_aval";
    public $timestamps = false;
    protected $primaryKey = 'informacion_contacto_id';
    protected $hidden = ['informacion_contacto_id', 'aval_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'informacion_contacto_id', 'aval_id', 'telefono_fijo', 'telefono_movil', 'telefono_alternativo_1', 'nombre_alternativo_1', 'parentesco_alternativo_1',
        'telefono_alternativo_2', 'nombre_alternativo_2', 'parentesco_alternativo_2', 'correo_electronico'
    ];

    public static function create(tbl_informacion_contacto_aval $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_informacion_contacto_aval $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_aval_id($aval_id)
    {
        $model = tbl_informacion_contacto_aval::where([
            ['aval_id', '=', $aval_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
