<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_cortes extends Model
{
    protected $table = "tbl_cortes";
    public $timestamps = false;
    protected $primaryKey = 'corte_id';

    protected $hidden = [
        'corte_id', 'usuario_id', 'activo', 'creado_por', 'fecha_creacion', 'usuario_entrego_id'
    ];

    protected $fillable = [
        'corte_id', 'usuario_id', 'fecha_cierre', 'cerrado', 'fondos'
    ];

    public static function create(tbl_cortes $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_cortes $model)
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
        $model = tbl_cortes::where([
            ['corte_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_usuario_id_abierto($usuario_id)
    {
        $model = tbl_cortes::where([
            ['usuario_id', '=', $usuario_id],
            ['cerrado', '=', false],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
