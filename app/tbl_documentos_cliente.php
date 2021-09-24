<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;

class tbl_documentos_cliente extends Model
{
    protected $table = 'tbl_documentos_cliente';
    public $timestamps = false;
    protected $primaryKey = 'documento_cliente_id';
    protected $hidden = ['documento_cliente_id', 'cliente_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'documento_cliente_id', 'cliente_id', 'documento', 'ruta', 'tipo', 'clave_identificacion'
    ];

    public static function create(tbl_documentos_cliente $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_documentos_cliente $model)
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
        $model = tbl_documentos_cliente::where([
            ['documento_cliente_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_buy_cliente_id($cliente_id)
    {
        $model = tbl_documentos_cliente::where([
            ['activo', '=', true],
            ['cliente_id', '=', $cliente_id]
        ])->orderby('fecha_creacion', 'desc')
            ->get();
        return $model;
    }
}
