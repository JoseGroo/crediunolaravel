<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_tarjetas extends Model
{
    protected $table = "tbl_tarjetas";
    public $timestamps = false;
    protected $primaryKey = 'tarjeta_id';


    protected $hidden = [
        'tarjeta_id'
    ];

    protected $fillable = [
        'tarjeta_id', 'numero_tarjeta', 'banco', 'tipo', 'nombre_propietario', 'importe_pagado'
    ];

    public static function create(tbl_tarjetas $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_tarjetas $model)
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
