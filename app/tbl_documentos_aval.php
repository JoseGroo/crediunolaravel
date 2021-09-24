<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_documentos_aval extends Model
{
    protected $table = 'tbl_documentos_aval';
    public $timestamps = false;
    protected $primaryKey = 'documento_aval_id';
    protected $hidden = ['documento_aval_id', 'aval_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'documento_aval_id', 'aval_id', 'documento', 'ruta', 'tipo', 'clave_identificacion'
    ];

    public static function create(tbl_documentos_aval $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_documentos_aval $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_id($id)
    {
        $model = tbl_documentos_aval::where([
            ['documento_aval_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_by_aval_id($aval_id)
    {
        $model = tbl_documentos_aval::where([
            ['activo', '=', true],
            ['aval_id', '=', $aval_id]
        ])->orderby('fecha_creacion', 'desc')
            ->get();
        return $model;
    }
}
