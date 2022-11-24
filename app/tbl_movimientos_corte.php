<?php

namespace App;

use App\Enums\estatus_movimientos_corte;
use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_movimientos_corte extends Model
{
    protected $table = "tbl_movimientos_corte";
    public $timestamps = false;
    protected $primaryKey = 'movimiento_corte_id';


    protected $hidden = [
        'movimiento_corte_id', 'cliente_id', 'corte_id', 'external_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'movimiento_corte_id', 'cliente_id', 'tipo', 'importe', 'corte_id', 'metodo_pago', 'external_id', 'estatus', 'cantidad_divisa', 'divisa'
    ];

    public static function get_list_by_ids($ids)
    {
        $model = tbl_movimientos_corte::query()
            ->whereIn('tbl_movimientos_corte.movimiento_corte_id', $ids)
            ->where('activo', '=', true)
            ->where('estatus', '=', estatus_movimientos_corte::Activo)
            ->get();

        return $model;
    }

    public static function get_list_by_corte_id($corte_id)
    {
        $model = tbl_movimientos_corte::query()
            ->where('tbl_movimientos_corte.corte_id', '=', $corte_id)
            ->where('tbl_movimientos_corte.activo', '=', true)
            ->leftJoin('tbl_clientes as cli', 'cli.cliente_id', '=', 'tbl_movimientos_corte.cliente_id')
            ->select([
                'corte_id',
                'cli.cliente_id',
                'cli.nombre',
                'cli.apellido_paterno',
                'tbl_movimientos_corte.movimiento_corte_id',
                'tbl_movimientos_corte.fecha_creacion',
                'tbl_movimientos_corte.tipo',
                'tbl_movimientos_corte.importe',
                'tbl_movimientos_corte.metodo_pago',
                'tbl_movimientos_corte.divisa_id',
                'tbl_movimientos_corte.cantidad_divisa',
                'tbl_movimientos_corte.estatus'
            ])
            ->orderBy('movimiento_corte_id', 'desc')
            ->get();

        return $model;
    }

    public static function create(tbl_movimientos_corte $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_movimientos_corte $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function cancel_by_ids($ids)
    {
        $affected = DB::table('tbl_movimientos_corte')
            ->whereIn('movimiento_corte_id', $ids)
            ->update(
                [
                    'estatus' => estatus_movimientos_corte::Cancelado
                ]
            );

        return $affected;
    }
}
