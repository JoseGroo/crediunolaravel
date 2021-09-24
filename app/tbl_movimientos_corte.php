<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_movimientos_corte extends Model
{
    protected $table = "tbl_movimientos_corte";
    public $timestamps = false;
    protected $primaryKey = 'movimiento_corte_id';


    protected $hidden = [
        'movimiento_corte_id', 'cliente_id', 'corte_id', 'external_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'movimiento_corte_id', 'cliente_id', 'tipo', 'importe', 'corte_id', 'metodo_pago', 'external_id', 'estatus', 'cantidad_divisa', 'divisa'
    ];

    public static function create(tbl_movimientos_corte $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_movimientos_corte $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }
}
