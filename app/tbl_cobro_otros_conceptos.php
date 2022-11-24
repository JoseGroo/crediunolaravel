<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_cobro_otros_conceptos extends Model
{
    protected $table = "tbl_cobro_otros_conceptos";
    public $timestamps = false;
    protected $primaryKey = 'cobro_otro_concepto_id';


    protected $fillable = [
        'cobro_otro_concepto_id', 'concepto', 'importe'
    ];


    public static function create(tbl_cobro_otros_conceptos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_cobro_otros_conceptos $model)
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
        $model = tbl_cobro_otros_conceptos::where([
            ['cobro_otro_concepto_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
