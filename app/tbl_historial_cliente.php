<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_historial_cliente extends Model
{
    protected $table = "tbl_historial_cliente";
    public $timestamps = false;
    protected $primaryKey = 'historial_cliente_id';
    protected $hidden = ['historial_cliente_id', 'cliente_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'historial_cliente_id', 'cliente_id', 'tiene_adeudo', 'acreedor', 'telefono', 'adeudo', 'esta_al_corriente'
    ];

    public static function create(tbl_historial_cliente $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_historial_cliente $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_cliente_id($cliente_id)
    {
        $model = tbl_historial_cliente::where([
            ['cliente_id', '=', $cliente_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
