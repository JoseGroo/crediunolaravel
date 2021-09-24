<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_contactos extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'contacto_id';
    protected $hidden = ['contacto_id', 'cliente_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'contacto_id', 'cliente_id', 'nombre', 'direccion', 'telefono', 'correo_electronico'
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
        //DB::enableQueryLog();
        $model = DB::table("tbl_contactos")
            ->where([
                ['contacto_id', '!=', $id],
                ['activo', '=', true]
            ])
            ->where(function($query) use($nombre, $telefono) {
                $query->where('nombre', $nombre);
                $query->orWhere('telefono', $telefono);
            })
            ->get()->count();

        //dd(DB::getQueryLog());
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

    public static function get_pagination($nombre, $telefono, $perPage)
    {

        $model = tbl_contactos::where([
            ['nombre', 'LIKE', '%'.$nombre.'%'],
            ['telefono', 'LIKE', '%'.$telefono.'%'],
            ['activo', '=', true],
        ])
            ->orderBy('nombre', 'asc')
            ->paginate($perPage);
        return $model;
    }
}
