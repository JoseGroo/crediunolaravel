<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_fondos extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'fondo_id';
    protected $hidden = ['fondo_id', 'activo', 'creado_por', 'fecha_creacion'];


    protected $fillable = [
        'fondo_id', 'fondo', 'tipo', 'banco', 'cuenta', 'ultimo_cheque_girado', 'importe_pesos','importe_dolares', 'importe_dolares_moneda', 'importe_euros'
    ];

    public static function create(tbl_fondos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_fondos $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_list()
    {
        $model = tbl_fondos::where([
            ['activo', '=', true]
        ])->orderby('fondo_id')
            ->get();
        return $model;
    }

    public static function check_if_exists($grupo, $id)
    {
        $model = tbl_fondos::where([
            ['fondo', '=', $grupo],
            ['fondo_id', '!=', $id]
        ])->get()->count();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_fondos::where([
            ['fondo_id', '=', $id],
        ])->first();
        return $model;
    }

    public static function get_pagination($grupo, $tipo_fondo, $perPage)
    {

        $model = tbl_fondos::where([
            ['fondo', 'LIKE', '%'.$grupo.'%'],
        ])
            ->where(function($query) use ($tipo_fondo) {

                if(!empty($tipo_fondo))
                    $query->where('tipo', $tipo_fondo);
            })
            ->orderBy('fondo', 'asc')
            ->paginate($perPage);
        return $model;
    }
}
