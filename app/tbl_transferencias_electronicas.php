<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_transferencias_electronicas extends Model
{
    protected $table = "tbl_transferencias_electronicas";
    public $timestamps = false;
    protected $primaryKey = 'transferencia_electronica_id';


    protected $hidden = [
        'transferencia_electronica_id'
    ];

    protected $fillable = [
        'transferencia_electronica_id', 'importe_transferencia', 'importe_pagado', 'banco', 'cuenta', 'nombre_titular'
    ];

    public static function create(tbl_transferencias_electronicas $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_transferencias_electronicas $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function tbl_cliente()
    {
        return $this->belongsTo(tbl_clientes::class, 'cliente_id', 'cliente_id');
    }

    public static function get_list()
    {
        $model = tbl_transferencias_electronicas::where([
            ['activo', '=', true]
        ])->get();
        return $model;
    }
}
