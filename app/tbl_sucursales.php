<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_sucursales extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'sucursal_id';
    protected $hidden = ['sucursal_id', 'estado_id', 'ciudad_id', 'activo', 'creado_por', 'fecha_creacion', 'ciudad', 'estado'];


    protected $fillable = [
        'sucursal_id', 'estado_id', 'ciudad_id', 'sucursal', 'numero_contrato', 'direccion', 'telefono', 'beneficiario', 'dolar_compra',
        'dolar_venta', 'euro_compra', 'euro_venta', 'dolar_moneda_compra', 'dolar_moneda_venta'
    ];

    public static function create(tbl_sucursales $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_sucursales $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function estado()
    {
        return $this->belongsTo(tbl_estados::class, 'estado_id', 'estado_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(tbl_ciudades::class, 'ciudad_id', 'ciudad_id');
    }

    public static function get_list()
    {
        $model = tbl_sucursales::where([
            ['activo', '=', true]
        ])->orderby('sucursal_id')
            ->get();
        return $model;
    }

    public static function check_if_exists($sucursal, $id)
    {
        $model = tbl_sucursales::where([
            ['sucursal', '=', $sucursal],
            ['sucursal_id', '!=', $id]
        ])->get()->count();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_sucursales::where([
            ['sucursal_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_pagination($sucursal, $estado_id, $ciudad_id, $perPage)
    {

        $model = tbl_sucursales::where([
                ['sucursal', 'LIKE', '%'.$sucursal.'%'],
                ['activo', '=', true]
            ])
            ->where(function($query) use ($estado_id, $ciudad_id) {
                if($estado_id > 0)
                    $query->where('estado_id', $estado_id);

                if($ciudad_id > 0)
                    $query->where('ciudad_id', $ciudad_id);
            })
            ->orderBy('sucursal', 'asc')
            ->paginate($perPage);
        return $model;
    }
}
