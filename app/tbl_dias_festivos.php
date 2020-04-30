<?php

namespace App;

use DateTime;
use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_dias_festivos extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'dia_festivo_id';
    protected $hidden = ['dia_festivo_id', 'activo', 'creado_por', 'fecha_creacion'];


    protected $fillable = [
        'dia_festivo_id', 'fecha', 'razon'
    ];

    public static function create(tbl_dias_festivos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_dias_festivos $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function check_if_exists($fecha, $id)
    {
        $fecha = DateTime::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
        $model = tbl_dias_festivos::where([
            ['dia_festivo_id', '!=', $id],
            ['activo', '=', true]
        ])->whereDate('fecha', $fecha)
            ->get()->count();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_dias_festivos::where([
            ['dia_festivo_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_pagination($fecha_inicio, $fecha_fin, $razon, $mostrar_dias_pasados, $perPage)
    {

        $model = tbl_dias_festivos::where([
            ['activo', '=', true],
        ])
            ->where(function($query) use ($fecha_inicio, $fecha_fin, $mostrar_dias_pasados, $razon) {


                if($mostrar_dias_pasados == null) {
                    $query->where('fecha', '>=', DB::raw('curdate()'));
                }

                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    $query->whereDate('fecha', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($razon)) {
                    $query->where('razon', 'LIKE', '%'.$razon.'%');
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
