<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;

class tbl_cargos_manuales extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'cargo_manual_id';
    protected $hidden = ['cargo_manual_id', 'activo', 'creado_por', 'fecha_creacion'];

    public static function create(tbl_cargos_manuales $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function get_pagination($fecha_inicio, $fecha_fin, $cliente, $perPage)
    {

        $model = DB::table('tbl_cargos_manuales AS car_man')
        ->where([
            ['car_man.activo', '=', true],
        ])
            ->where(function($query) use ($fecha_inicio, $fecha_fin, $cliente) {


                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    $query->whereDate('car_man.fecha_creacion', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($fecha_fin)) {
                    $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
                    $query->whereDate('car_man.fecha_creacion', '<=', $fecha_fin->format('Y-m-d'));
                }

                if(!empty($cliente)) {
                    $query->where(DB::raw("CONCAT(cli.cliente_id, '-', COALESCE(cli.nombre,''), ' ', COALESCE(cli.apellido_paterno,''), ' ', COALESCE(cli.apellido_materno,''))"), 'LIKE', "%".$cliente."%");
                }
            })
            ->join('tbl_clientes as cli', 'cli.cliente_id', '=', 'car_man.cliente_id')
            ->join('tbl_adeudos as adeu', 'adeu.adeudo_id', '=', 'car_man.adeudo_id')
            ->orderBy('car_man.fecha_creacion', 'desc')
            ->select([
                "cli.nombre",
                "cli.apellido_paterno",
                "cli.apellido_materno",
                "car_man.prestamo_id",
                "adeu.recibo",
                "car_man.importe",
                "car_man.comentario",
            ])
            ->paginate($perPage);
        return $model;
    }
}
