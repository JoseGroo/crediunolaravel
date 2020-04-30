<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_intereses extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'interes_id';
    protected $hidden = ['interes_id', 'activo'];

    protected $fillable = [
        'interes_id', 'nombre', 'interes_mensual', 'interes_diario', 'otros_intereses', 'iva', 'comision_apertura', 'manejo_cuenta',
        'gastos_papeleria', 'gastos_cobranza', 'seguro_desempleo', 'iva_otros_conceptos', 'imprimir_logo', 'imprimir_datos_empresa'
    ];

    public static function edit(tbl_intereses $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_list()
    {
        $model = tbl_intereses::where([
            ['activo', '=', true]
        ])->orderby('interes_id')
            ->get();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_intereses::where([
            ['interes_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }
}
