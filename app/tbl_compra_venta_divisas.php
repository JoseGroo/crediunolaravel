<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_compra_venta_divisas extends Model
{
    protected $table = "tbl_compra_venta_divisas";
    public $timestamps = false;
    protected $primaryKey = 'compra_venta_divisa_id';

    /*protected $hidden = [
        'compra_venta_divisa_id', 'divisa_id', 'activo', 'creado_por', 'fecha_creacion'
    ];*/

    protected $fillable = [
        'compra_venta_divisa_id', 'divisa_id', 'cantidad', 'importe', 'tipo', 'importe_iva'
    ];

    public function tbl_divisa()
    {
        return $this->belongsTo(tbl_divisas::class, 'divisa_id', 'divisa_id');
    }

    public static function create(tbl_compra_venta_divisas $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_compra_venta_divisas $model)
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
        $model = tbl_compra_venta_divisas::where([
            ['compra_venta_divisa_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
