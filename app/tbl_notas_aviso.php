<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_notas_aviso extends Model
{
    protected $table = "tbl_notas_aviso";
    public $timestamps = false;
    protected $primaryKey = 'nota_aviso_id';


    protected $fillable = [
        'nota_aviso_id', 'cliente_id', 'nota'
    ];


    public static function create(tbl_notas_aviso $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_notas_aviso $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function tbl_creado_por()
    {
        return $this->belongsTo(tbl_usuarios::class, 'creado_por', 'id');
    }


    public static function get_list_by_cliente_id_no_visto($cliente_id)
    {
        $model = tbl_notas_aviso::where([
            ['activo', '=', true],
            ['visto', '=', false],
            ['cliente_id', '=', $cliente_id],
        ])->orderby('fecha_creacion', 'desc')
            ->get();
        return $model;
    }

    public static function get_list_by_cliente_id($cliente_id)
    {
        $model = tbl_notas_aviso::where([
            ['activo', '=', true],
            ['cliente_id', '=', $cliente_id],
        ])->orderby('fecha_creacion', 'desc')
            ->get();
        return $model;
    }
}
