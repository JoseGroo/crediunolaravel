<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_informacion_laboral_aval extends Model
{
    protected $table = 'tbl_informacion_laboral_aval';
    public $timestamps = false;
    protected $primaryKey = 'informacion_laboral_id';
    protected $hidden = [
        'informacion_laboral_id', 'aval_id', 'estado_id',
        'activo', 'creado_por', 'fecha_creacion', 'ciudad', 'estado'
    ];

    protected $fillable = [
        'informacion_laboral_id', 'aval_id', 'empresa', 'pais', 'estado_id', 'localidad',
        'colonia', 'numero_exterior', 'calle', 'codigo_postal', 'jefe_inmediato', 'telefono',
        'departamento', 'antiguedad', 'unidad_antiguedad'
    ];

    public static function create(tbl_informacion_laboral_aval $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_informacion_laboral_aval $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    #region Objetos de llaves foraneas
    public function estado()
    {
        return $this->belongsTo(tbl_estados::class, 'estado_id', 'estado_id');
    }
    #endregion


    public static function get_by_aval_id($aval_id)
    {
        $model = tbl_informacion_laboral_aval::where([
            ['aval_id', '=', $aval_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
