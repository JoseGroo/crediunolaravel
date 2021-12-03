<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class tbl_cortes extends Model
{
    protected $table = "tbl_cortes";
    public $timestamps = false;
    protected $primaryKey = 'corte_id';

    /*protected $hidden = [
        'corte_id', 'usuario_id', 'activo', 'creado_por', 'fecha_creacion', 'usuario_entrego_id'
    ];*/

    protected $fillable = [
        'corte_id', 'usuario_id', 'fecha_cierre', 'cerrado', 'fondos'
    ];

    public static function create(tbl_cortes $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_cortes $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }


    public static function get_pagination($fecha_inicio, $fecha_fin, $sucursal_id, $perPage)
    {

        $model = tbl_cortes::query()
        ->where([
            ['tbl_cortes.activo', '=', true],
        ])
            ->where(function($query) use ($fecha_inicio, $fecha_fin, $sucursal_id) {
                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    //$query->whereDate('tbl_cortes.fecha_creacion', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($fecha_fin)) {
                    $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
                    $query->whereDate('tbl_cortes.fecha_creacion', '<=', $fecha_fin->format('Y-m-d'));
                }

                if(!empty($sucursal_id)) {
                    $query->where('tbl_usuarios.sucursal_id', 'LIKE', '%'.$sucursal_id.'%');
                }
            })
            ->join('tbl_usuarios', 'tbl_usuarios.id', '=', 'tbl_cortes.usuario_id')
            ->select([
                'tbl_cortes.fecha_creacion',
                'tbl_cortes.corte_id',
                'tbl_cortes.usuario_id',
                'tbl_cortes.cerrado'
            ])
            ->orderBy('tbl_cortes.fecha_creacion', 'asc')
            ->paginate($perPage);
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_cortes::where([
            ['corte_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_usuario_id_abierto($usuario_id)
    {
        $model = tbl_cortes::where([
            ['usuario_id', '=', $usuario_id],
            ['cerrado', '=', false],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    #region Objetos de llaves foraneas
    public function tbl_usuario()
    {
        return $this->belongsTo(tbl_usuarios::class, 'usuario_id', 'id');
    }

    #endregion
}
