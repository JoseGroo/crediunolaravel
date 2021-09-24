<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_pagos extends Model
{
    protected $table = "tbl_pagos";
    public $timestamps = false;
    protected $primaryKey = 'pago_id';


    protected $hidden = [
        'pago_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'pago_id', 'importe', 'external_id', 'tipo', 'descuento_id', 'capital', 'interes', 'iva', 'cambio', 'estatus'
    ];

    public static function create(tbl_pagos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_pagos $model)
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
