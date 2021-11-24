<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_fichas_deposito extends Model
{
    protected $table = "tbl_fichas_deposito";
    public $timestamps = false;
    protected $primaryKey = 'ficha_deposito_id';


    protected $hidden = [
        'ficha_deposito_id'
    ];

    protected $fillable = [
        'ficha_deposito_id', 'importe_ficha_deposito', 'importe_pagado', 'cuenta_receptora', 'banco', 'cuentahabiente'
    ];

    public static function create(tbl_fichas_deposito $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_fichas_deposito $model)
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
        $model = tbl_fichas_deposito::where([
            ['activo', '=', true]
        ])->get();
        return $model;
    }
}
