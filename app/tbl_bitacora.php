<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tbl_bitacora extends Model
{
    protected $table = 'tbl_bitacora';
    public $timestamps = false;
    protected $primaryKey = 'bitacora_id';

    public static function create(tbl_bitacora $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function get_by_id($id)
    {
        $model = tbl_bitacora::where([
            ['bitacora_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list()
    {
        $model = tbl_bitacora::where([
            ['activo', '=', true]
        ])->orderby('bitacora_id', 'desc')
            ->get();
        return $model;
    }

    public static function get_pagination($usuario, $fecha_inicio, $fecha_fin, $catalago_sistema, $perPage)
    {

        $model = DB::table('tbl_bitacora AS bita')
            ->where(function($query) use ($usuario, $fecha_inicio, $fecha_fin, $catalago_sistema) {

                if(!empty($usuario))
                    $query->where(DB::raw("CONCAT(COALESCE(usu.nombre,''), ' ', COALESCE(usu.apellido_paterno,''), ' ', COALESCE(usu.apellido_materno,''))"), 'LIKE', "%".$usuario."%");

                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    $query->whereDate('bita.fecha', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($fecha_fin)) {
                    $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
                    $query->whereDate('bita.fecha', '<=', $fecha_fin->format('Y-m-d'));
                }

                if(!empty($catalago_sistema))
                    $query->where('bita.catalago_sistema', $catalago_sistema);
            })
            ->join('tbl_usuarios AS usu', 'bita.usuario_id', '=', 'usu.id')
            ->orderBy('bita.bitacora_id', 'desc')
            ->select('usu.nombre', 'usu.apellido_paterno', 'usu.apellido_materno', 'bita.fecha', 'bita.bitacora_id', 'bita.fecha', 'bita.movimiento', 'bita.catalago_sistema')
            ->paginate($perPage);

        return $model;

        /*

        $model = DB::table('tbl_usuarios AS usu')
        ->where(DB::raw("CONCAT(COALESCE(usu.nombre,''), ' ', COALESCE(usu.apellido_paterno,''), ' ', COALESCE(usu.apellido_materno,''))"), 'LIKE', "%".$nombre."%")
        ->join('tbl_roles as rol', 'usu.rol_id', '=', 'rol.rol_id')
        ->orderBy('usu.activo', 'desc')
        ->orderBy('usu.rol_id', 'asc')
        ->orderBy('usu.nombre', 'asc')
        ->orderBy('usu.apellido_paterno', 'asc')
        ->orderBy('usu.apellido_materno', 'asc')
        ->select('usu.id', 'usu.nombre', 'usu.apellido_paterno', 'usu.apellido_materno', 'usu.usuario', 'rol.rol', 'usu.activo', 'usu.foto')
        ->paginate($perPage);

         * */
    }

    public function usuario()
    {
        return $this->belongsTo(tbl_usuarios::class, 'usuario_id', 'id');
    }

    /*$bitacora = tbl_bitacora::get_list();

        foreach ($bitacora as $item)
        {
            if($item->datos_anteriores)
            {
                //dd(json_decode($item->datos_anteriores, true));
                $model = new tbl_sucursales(json_decode($item->datos_anteriores, true));
                dd($model);
            }
        }*/
}
