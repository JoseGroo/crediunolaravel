<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_cheques extends Model
{
    protected $table = "tbl_cheques";
    public $timestamps = false;
    protected $primaryKey = 'cheque_id';


    protected $hidden = [
        'cheque_id'
    ];

    protected $fillable = [
        'cheque_id', 'importe_cheque', 'importe_pagado', 'banco', 'numero_cheque', 'numero_cuenta'
    ];

    public static function create(tbl_cheques $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_cheques $model)
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
        $model = tbl_cheques::where([
            ['activo', '=', true]
        ])->get();
        return $model;
    }
}
