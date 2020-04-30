<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_contactos extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'contacto_id';
    protected $hidden = ['contacto_id', 'activo', 'creado_por', 'fecha_creacion'];


    protected $fillable = [
        'contacto_id', 'nombre', 'direccion', 'telefono', 'correo_electronico', 'sucursal_id'
    ];

    public static function create(tbl_contactos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_contactos $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function check_if_exists($nombre, $telefono, $id)
    {
        DB::enableQueryLog();
        $model = \DB::table("tbl_contactos")
        ->where([
            ['contacto_id', '!=', $id],
            ['activo', '=', true]
        ])
            ->orWhere('nombre', $nombre)
            ->orWhere('telefono', $telefono)
            ->get()->count();

        dd(DB::getQueryLog());
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_contactos::where([
            ['contacto_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_pagination($fecha_inicio, $fecha_fin, $razon, $mostrar_dias_pasados, $perPage)
    {

        $model = tbl_contactos::where([
            ['razon', 'LIKE', '%'.$razon.'%'],
            ['activo', '=', true],
        ])
            ->where(function($query) use ($fecha_inicio, $fecha_fin, $mostrar_dias_pasados) {


                if($mostrar_dias_pasados == null) {
                    $query->where('fecha', '>=', DB::raw('curdate()'));
                }

                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    $query->whereDate('fecha', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($fecha_fin)) {
                    $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
                    $query->whereDate('fecha', '<=', $fecha_fin->format('Y-m-d'));
                }
            })
            ->orderBy('fecha', 'asc')
            ->paginate($perPage);
        return $model;
    }
}
