<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_conyuge_cliente extends Model
{
    protected $table = 'tbl_conyuge_cliente';
    public $timestamps = false;
    protected $primaryKey = 'conyuge_cliente_id';
    protected $hidden = [
        'conyuge_cliente_id', 'cliente_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'conyuge_cliente_id', 'cliente_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento',
        'telefono_movil', 'lugar_trabajo', 'puesto', 'jefe'
    ];

    public static function create(tbl_conyuge_cliente $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_conyuge_cliente $model)
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
        $model = tbl_conyuge_cliente::where([
            ['conyuge_cliente_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_cliente_id($cliente_id)
    {
        $model = tbl_conyuge_cliente::where([
            ['cliente_id', '=', $cliente_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
