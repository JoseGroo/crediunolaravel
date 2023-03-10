<?php

namespace App;

use App\Enums\estatus_adeudos;
use App\Enums\estatus_prestamo;
use App\Enums\formas_pago;
use App\Enums\tipo_pago;
use DB;
use Illuminate\Database\Eloquent\Model;


class tbl_cargos extends Model
{
    protected $table = "tbl_cargos";
    public $timestamps = false;
    protected $primaryKey = 'cargo_id';


    protected $hidden = [
        'cargo_id', 'adeudo_id', 'prestamo_id', 'activo', 'fecha_creacion'
    ];

    protected $fillable = [
        'adeudo_id', 'interes', 'prestamo_id', 'estatus', 'fecha_ultima_actualizacion'
    ];

    public static function create(tbl_cargos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit_model(tbl_cargos $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function edit($iva, $interes, $pago_total, $fecha, $id)
    {
        $affected = DB::table('tbl_cargos')
            ->where('cargo_id', $id)
            ->update(
                [
                    'interes' => $interes,
                    'iva' => $iva,
                    'importe_total' => $pago_total,
                    'fecha_ultima_actualizacion' => $fecha,
                    'estatus' => estatus_adeudos::Vigente
                ]
            );

        return $affected;
    }

    public static function delete_by_prestamo_id($prestamo_id)
    {
        $affected = DB::table('tbl_cargos')
            ->where('prestamo_id', $prestamo_id)
            ->update(
                [
                    'activo' => false
                ]
            );

        return $affected;
    }

    public static function get_by_id($id)
    {
        $model = tbl_cargos::where([
            ['cargo_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_adeudo_id($id)
    {
        $model = tbl_cargos::where([
            ['adeudo_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }


    public static function get_list_for_cargos()
    {
        //DB::enableQueryLog();
        $model = DB::table('tbl_cargos AS carg')
            ->join('tbl_adeudos AS adeu', 'adeu.adeudo_id', '=', 'carg.adeudo_id')
            ->join('tbl_prestamos AS pres', 'carg.prestamo_id', '=', 'pres.prestamo_id')
            ->join('tbl_intereses AS inte', 'pres.interes_id', '=', 'inte.interes_id')
            ->where([
                ['carg.activo', '=', true],
                ['adeu.activo', '=', true],
                ['pres.activo', '=', true],
                ['inte.activo', '=', true],
                ['adeu.estatus', '=', estatus_adeudos::Vigente],
                //['carg.estatus', '=', estatus_adeudos::Vigente],
                ['pres.estatus', '=', estatus_prestamo::Vigente],
                ['carg.fecha_ultima_actualizacion', '<', DB::raw('CURDATE()')]
            ])
            ->select([
                "carg.cargo_id",
                "carg.fecha_ultima_actualizacion",
                "pres.capital AS capital_prestamo",
                "inte.interes_diario",
                "inte.iva AS iva_interes",
                "pres.interes_id",
                "pres.taza_iva",
                "inte.interes_mensual",
                "carg.interes",
                "carg.iva",
                "carg.importe_total"
            ])->get();
        //dd(DB::getQueryLog());
        return $model;
    }

    public static function get_list_by_cliente_id($cliente_id)
    {
        $model = DB::table('tbl_cargos')
            ->join('tbl_prestamos', 'tbl_prestamos.prestamo_id', '=', 'tbl_cargos.prestamo_id')
            ->select('tbl_cargos.*')
            ->where('tbl_prestamos.cliente_id', '=', $cliente_id)
            ->where('tbl_cargos.estatus', '=', estatus_adeudos::Vigente)
            ->get();

        return $model;
    }

    public function tbl_adeudo()
    {
        return $this->belongsTo(tbl_adeudos::class, 'adeudo_id', 'adeudo_id');
    }

    public function tbl_pagos()
    {
        $model = $this->hasMany(tbl_pagos::class, 'external_id', 'cargo_id')->where([
            ['activo', '=', true],
            ['tipo', '=', tipo_pago::Cargo],
        ]);
        return $model;
    }

    #region Attributes

    public function getImportePagadoAttribute()
    {
        return $this->tbl_pagos->sum('importe');
    }

    public function getImportePagadoDescuentoAttribute()
    {
        return $this->tbl_pagos->where('metodo_pago', formas_pago::Descuento)->sum('importe');
    }

    public function getTotalPagosDescuentoAttribute()
    {
        return $this->tbl_pagos->where('metodo_pago', formas_pago::Descuento)->count();
    }

    public function getImportePagadoRefinanciamientoAttribute()
    {
        return $this->tbl_pagos->where('metodo_pago', formas_pago::Refinanciar)->sum('importe');
    }

    public function getTotalPagosRefinanciamientoAttribute()
    {
        return $this->tbl_pagos->where('metodo_pago', formas_pago::Refinanciar)->count();
    }

    #endregion
}
