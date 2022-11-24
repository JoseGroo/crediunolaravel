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


    public static function get_pagination($fecha_inicio, $fecha_fin, $sucursal_id, $estatus, $perPage)
    {

        $model = tbl_cortes::query()
        ->where([
            ['tbl_cortes.activo', '=', true],
        ])
            ->where(function($query) use ($fecha_inicio, $fecha_fin, $sucursal_id, $estatus) {
                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    $query->whereDate('tbl_cortes.fecha_creacion', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($fecha_fin)) {
                    $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
                    $query->whereDate('tbl_cortes.fecha_creacion', '<=', $fecha_fin->format('Y-m-d'));
                }

                if(!empty($sucursal_id)) {
                    $query->where('tbl_usuarios.sucursal_id', 'LIKE', '%'.$sucursal_id.'%');
                }

                if(!empty($estatus)) {
                    $cerrado = $estatus == 1 ? true : false;
                    $query->where('tbl_cortes.cerrado', '=', $cerrado);
                }
            })
            ->join('tbl_usuarios', 'tbl_usuarios.id', '=', 'tbl_cortes.usuario_id')
            ->select([
                'tbl_cortes.fecha_creacion',
                'tbl_cortes.corte_id',
                'tbl_cortes.usuario_id',
                'tbl_cortes.cerrado',
                \DB::raw("
                    (
                    IFNULL((
                        SELECT SUM(mov.importe)
                        FROM tbl_movimientos_corte mov
                        WHERE mov.activo = 1 AND mov.estatus = 1 AND mov.corte_id = tbl_cortes.corte_id
                            AND mov.metodo_pago IN(1, 7, 8)
                            AND mov.tipo IN(2, 3, 5, 6, 7)
                            AND (mov.metodo_pago = 1 AND mov.tipo != 4)
                    ),0)
                    +
                    IFNULL((
                        SELECT SUM(CASE WHEN tra_fon.tipo = 1 THEN tra_fon.importe ELSE -tra_fon.importe END)
                        FROM tbl_transferencias_fondos tra_fon
                        WHERE tra_fon.activo = 1 AND tra_fon.estatus = 1
                            AND tra_fon.corte_id = tbl_cortes.corte_id AND tra_fon.divisa_id = 0
                    ),0)
                    +
                    IFNULL((
                        SELECT SUM(CASE WHEN tra_caj.corte_destino_id = tbl_cortes.corte_id THEN tra_caj.importe ELSE -tra_caj.importe END)
                        FROM tbl_transferencias_entre_cajas tra_caj
                        WHERE tra_caj.activo = 1 AND tra_caj.estatus = 1 AND tra_caj.divisa_id = 0
                            AND (tra_caj.corte_destino_id = tbl_cortes.corte_id OR tra_caj.corte_origen_id = tbl_cortes.corte_id)
                    ),0)
                    -
                    IFNULL((
                        SELECT SUM(mov.importe)
                        FROM tbl_movimientos_corte mov
                        WHERE mov.activo = 1 AND mov.estatus = 1 AND mov.corte_id = tbl_cortes.corte_id
                            AND mov.metodo_pago IN(1)
                            AND mov.tipo IN(1,4)
                    ),0)
                    -
                    IFNULL((
                        SELECT SUM(pag.cambio)
                        FROM tbl_movimientos_corte mov
                        INNER JOIN tbl_pagos pag ON pag.pago_id = mov.external_id AND pag.cambio > 0
                        WHERE mov.activo = 1 AND mov.estatus = 1 AND mov.tipo IN(2,3) AND mov.corte_id = tbl_cortes.corte_id
                    ),0)
                  ) AS importe_moneda_nacional
                "),
                \DB::raw("
                (
                IFNULL((
                    SELECT SUM(CASE WHEN tipo = 4 THEN mov.cantidad_divisa ELSE - mov.cantidad_divisa END)
                    FROM tbl_movimientos_corte mov
                    WHERE mov.activo = 1 AND mov.estatus = 1 AND mov.corte_id = tbl_cortes.corte_id
                        AND mov.metodo_pago IN(1)
                        AND mov.tipo IN(4,5)
                ),0)
                +
                IFNULL((
                    SELECT SUM(CASE WHEN tra_fon.tipo = 1 THEN tra_fon.importe ELSE -tra_fon.importe END)
                    FROM tbl_transferencias_fondos tra_fon
                    WHERE tra_fon.activo = 1 AND tra_fon.estatus = 1
                        AND tra_fon.corte_id = tbl_cortes.corte_id AND tra_fon.divisa_id = 1
                ),0)
                +
                IFNULL((
                    SELECT SUM(CASE WHEN tra_caj.corte_destino_id = tbl_cortes.corte_id THEN tra_caj.importe ELSE -tra_caj.importe END)
                    FROM tbl_transferencias_entre_cajas tra_caj
                    WHERE tra_caj.activo = 1 AND tra_caj.estatus = 1 AND tra_caj.divisa_id = 1
                        AND (tra_caj.corte_destino_id = tbl_cortes.corte_id OR tra_caj.corte_origen_id = tbl_cortes.corte_id)
                ),0)
              ) importe_dolares"),
                \DB::raw("
                (
                IFNULL((
                    SELECT SUM(CASE WHEN tipo = 4 THEN mov.cantidad_divisa ELSE - mov.cantidad_divisa END)
                    FROM tbl_movimientos_corte mov
                    WHERE mov.activo = 1 AND mov.estatus = 1 AND mov.corte_id = tbl_cortes.corte_id
                        AND mov.metodo_pago IN(1) AND mov.tipo IN(4,5) AND mov.divisa_id = 2
                ),0)
                +
                IFNULL((
                    SELECT SUM(CASE WHEN tra_fon.tipo = 1 THEN tra_fon.importe ELSE -tra_fon.importe END)
                    FROM tbl_transferencias_fondos tra_fon
                    WHERE tra_fon.activo = 1 AND tra_fon.estatus = 1
                        AND tra_fon.corte_id = tbl_cortes.corte_id AND tra_fon.divisa_id = 2
                ),0)
                +
                IFNULL((
                    SELECT SUM(CASE WHEN tra_caj.corte_destino_id = tbl_cortes.corte_id THEN tra_caj.importe ELSE -tra_caj.importe END)
                    FROM tbl_transferencias_entre_cajas tra_caj
                    WHERE tra_caj.activo = 1 AND tra_caj.estatus = 1 AND tra_caj.divisa_id = 2
                        AND (tra_caj.corte_destino_id = tbl_cortes.corte_id OR tra_caj.corte_origen_id = tbl_cortes.corte_id)
                ),0)
              ) importe_dolares_m")
            ])
            ->orderBy('tbl_cortes.fecha_creacion', 'desc')
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

    public static function get_list_abiertos($not_id)
    {
        $model = tbl_cortes::where([
            ['cerrado', '=', false],
            ['activo', '=', true],
            ['corte_id', '!=', $not_id],
        ])->get();
        return $model;
    }

    #region Objetos de llaves foraneas
    public function tbl_usuario()
    {
        return $this->belongsTo(tbl_usuarios::class, 'usuario_id', 'id');
    }
    #endregion

    public function getNombreCompletoUsuarioAttribute()
    {
        return $this->tbl_usuario->nombre.' '.$this->tbl_usuario->apellido_paterno;
    }
}
