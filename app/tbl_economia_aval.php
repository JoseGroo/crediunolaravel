<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_economia_aval extends Model
{
    protected $table = 'tbl_economia_aval';
    public $timestamps = false;
    protected $primaryKey = 'economia_id';
    protected $hidden = [
        'economia_id', 'aval_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'economia_id', 'aval_id', 'ingresos_propios', 'ingresos_conyuge', 'otros_ingresos', 'gastos_fijos',
        'gastos_eventuales'
    ];

    public static function create(tbl_economia_aval $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_economia_aval $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_aval_id($aval_id)
    {
        $model = tbl_economia_aval::where([
            ['aval_id', '=', $aval_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
