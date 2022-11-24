<?php

namespace App;

use App\Enums\estatus_adeudos;
use App\Enums\estatus_prestamo;
use App\Enums\formas_pago;
use App\Enums\tipo_pago;
use App\Helpers\HelperCrediuno;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class tbl_adeudos extends Model
{

    protected $table = "tbl_adeudos";
    public $timestamps = false;
    protected $primaryKey = 'adeudo_id';


    protected $hidden = [
        'adeudo_id'//, 'prestamo_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'adeudo_id', 'prestamo_id', 'importe_total', 'capital', 'interes', 'iva', 'fecha_limite_pago', 'prestamo_id', 'numero_pago', 'estatus'
    ];

    private static function query_cobranza($sucursal_id, $ciudad_id, $fecha_inicio, $fecha_fin, $cliente_id, $grupo_id, $interes_id, $mostrar_liquidados){
        $query = tbl_adeudos::query()
            ->join('tbl_prestamos as pres', 'pres.prestamo_id', '=', 'tbl_adeudos.prestamo_id')
            ->join('tbl_clientes as clie', 'clie.cliente_id', '=', 'pres.cliente_id')
            ->join('tbl_sucursales as sucu', 'sucu.sucursal_id', '=', 'clie.sucursal_id')
            ->leftJoin('tbl_cargos as carg', 'carg.adeudo_id', '=', 'tbl_adeudos.adeudo_id')
            ->where([
                ['tbl_adeudos.activo', '=', true],
                ['clie.mostrar_cobranza', '=', true],
                ['pres.estatus', '=', estatus_prestamo::Vigente],
            ]);

        if($sucursal_id > 0)
            $query = $query->where('clie.sucursal_id', '=', $sucursal_id);

        if($ciudad_id > 0)
            $query = $query->where('sucu.ciudad_id', '=', $ciudad_id);

        if(!empty($fecha_inicio)) {
            $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
            $query->whereDate('tbl_adeudos.fecha_limite_pago', '>=', $fecha_inicio->format('Y-m-d'));
        }

        if(!empty($fecha_fin)) {
            $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
            $query->whereDate('tbl_adeudos.fecha_limite_pago', '<=', $fecha_fin->format('Y-m-d'));
        }

        if($cliente_id > 0)
            $query = $query->where('clie.cliente_id', '=', $cliente_id);

        if($grupo_id > 0)
            $query = $query->where('clie.grupo_id', '=', $grupo_id);

        if($interes_id > 0)
            $query = $query->where('pres.interes_id', '=', $interes_id);

        if(!$mostrar_liquidados)
        {
            $query = $query->where(function($subquery) {
                $subquery->where('carg.estatus', '=', estatus_adeudos::Vigente)->orWhere('tbl_adeudos.estatus', '=', estatus_adeudos::Vigente);
            });
        }
        return $query;
    }

    public static function get_index_cobranza($sucursal_id, $ciudad_id, $fecha_inicio, $fecha_fin, $cliente_id, $grupo_id, $interes_id, $mostrar_liquidados, $perPage){

        DB::enableQueryLog();
        $query = tbl_adeudos::query_cobranza($sucursal_id, $ciudad_id, $fecha_inicio, $fecha_fin, $cliente_id, $grupo_id, $interes_id, $mostrar_liquidados);
        $query = $query->select(
            'clie.cliente_id',
            'clie.nombre',
            'clie.apellido_paterno',
            'clie.apellido_materno',
            'clie.foto',
            'clie.estatus',
            'pres.prestamo_id',
            'tbl_adeudos.fecha_limite_pago',
            'tbl_adeudos.numero_pago',
            'tbl_adeudos.importe_total',
            'carg.importe_total AS importe_cargo',
        )->orderBy('clie.cliente_id')
            ->orderBy('tbl_adeudos.adeudo_id');
        //FALTA PONER ENTRE PARENTESIS WHERE DE ESTATUS CARGOS Y ADEUDOS
        //dd(DB::getQueryLog());
        return $query->paginate($perPage);
    }

    public static function get_totales_cobranza($sucursal_id, $ciudad_id, $fecha_inicio, $fecha_fin, $cliente_id, $grupo_id, $interes_id, $mostrar_liquidados){
        $query = tbl_adeudos::query_cobranza($sucursal_id, $ciudad_id, $fecha_inicio, $fecha_fin, $cliente_id, $grupo_id, $interes_id, $mostrar_liquidados);
        $query = $query->select(
            'tbl_adeudos.importe_total',
            'carg.importe_total AS importe_cargo',
        )->get();
        return $query;
    }

    public static function create_list($list_model)
    {
        try{
            $adeudos = new tbl_adeudos();
            $adeudos->fill($list_model);
            return ['saved' => $adeudos->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function create(tbl_adeudos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_adeudos $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function delete_by_prestamo_id($prestamo_id)
    {
        $affected = DB::table('tbl_adeudos')
            ->where('prestamo_id', $prestamo_id)
            ->update(
                [
                    'activo' => false
                ]
            );

        return $affected;
    }

    public static function get_total_adeudo_cliente_id($cliente_id){
        $query = tbl_adeudos::query()
            ->join('tbl_prestamos as pres', 'pres.prestamo_id', '=', 'tbl_adeudos.prestamo_id')
            ->where([
                ['pres.cliente_id', '=', $cliente_id],
                ['tbl_adeudos.activo', '=', true],
                ['pres.activo', '=', true],
                ['tbl_adeudos.estatus', '=', estatus_adeudos::Vigente],
                ['pres.estatus', '=', estatus_prestamo::Vigente],
            ]);
        return $query->sum('tbl_adeudos.importe_total');
    }

    public static function get_total_pagos_cliente_id($cliente_id){
        $query = tbl_adeudos::query()
            ->join('tbl_prestamos as pres', 'pres.prestamo_id', '=', 'tbl_adeudos.prestamo_id')
            ->where([
                ['pres.cliente_id', '=', $cliente_id],
                ['tbl_adeudos.activo', '=', true],
                ['pres.activo', '=', true],
                ['tbl_adeudos.estatus', '=', estatus_adeudos::Vigente],
                ['pres.estatus', '=', estatus_prestamo::Vigente],
            ]);
        return $query->count('tbl_adeudos.importe_total');
    }

    #region Objetos de llaves foraneas

    public function tbl_cargos()
    {
        $model = $this->hasMany(tbl_cargos::class, 'adeudo_id', 'adeudo_id');
        return $model->where('activo', '=', true);
    }

    public function tbl_pagos()
    {
        $model = $this->hasMany(tbl_pagos::class, 'external_id', 'adeudo_id')->where([
            ['activo', '=', true],
            ['tipo', '=', tipo_pago::Adeudo],
        ]);
        return $model;
    }

    #endregion

    #region Attributes

    public function getImportePagadoAttribute()
    {
        return $this->tbl_pagos->where('metodo_pago','!=', formas_pago::Descuento)->sum('importe');
    }

    public function getTblCargoAttribute()
    {
        return $this->tbl_cargos->first();
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

    public static function get_by_id($id)
    {
        $model = tbl_adeudos::where([
            ['adeudo_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_for_cargos()
    {
        //DB::enableQueryLog();
        $model = DB::table('tbl_adeudos AS adeu')
            ->join('tbl_prestamos AS pres', 'adeu.prestamo_id', '=', 'pres.prestamo_id')
            ->join('tbl_intereses AS inte', 'pres.interes_id', '=', 'inte.interes_id')
            ->where([
                ['adeu.activo', '=', true],
                ['pres.activo', '=', true],
                ['inte.activo', '=', true],
                ['adeu.estatus', '=', estatus_adeudos::Vigente],
                ['pres.estatus', '=', estatus_prestamo::Vigente],
                ['adeu.fecha_limite_pago', '<', DB::raw('CURDATE()')]
            ])->whereNotIn('adeu.adeudo_id', function($query){

                $query->select('adeudo_id')
                    ->from('tbl_cargos')
                    ->where([
                        ["activo", true]
                        //["estatus", estatus_adeudos::Vigente],
                    ])->get();
            })
            ->select([
                "adeu.adeudo_id",
                "adeu.capital",
                "adeu.interes",
                "adeu.iva",
                "adeu.fecha_limite_pago",
                "adeu.prestamo_id",
                "adeu.numero_pago",
                "adeu.estatus",
                "pres.capital AS capital_prestamo",
                "inte.interes_diario",
                "adeu.importe_total",
                "inte.iva AS iva_interes",
                "pres.interes_id",
                "pres.taza_iva",
                "inte.interes_mensual",
            ])->get();
        //dd(DB::getQueryLog());
        return $model;
    }

    public static function get_list_by_ids($ids)
    {
        $model = tbl_adeudos::where([
            ['estatus', '=', estatus_adeudos::Vigente],
            ['activo', '=', true]
        ])
        ->whereIn('adeudo_id', $ids)
        ->get();
        return $model;
    }

    public static function get_list_by_cliente_id($cliente_id)
    {
        $model = DB::table('tbl_adeudos')
            ->join('tbl_prestamos', 'tbl_prestamos.prestamo_id', '=', 'tbl_adeudos.prestamo_id')
            ->select('tbl_adeudos.*')
            ->where('tbl_prestamos.cliente_id', '=', $cliente_id)
            ->where('tbl_prestamos.activo', '=', 1)
            ->where('tbl_adeudos.activo', '=', 1)
            ->where('tbl_adeudos.estatus', '=', estatus_adeudos::Vigente)
            ->where('tbl_prestamos.estatus', '=', estatus_prestamo::Vigente)
            ->get();

        return $model;
    }

    public static function get_list_by_prestamo_id($prestamo_id)
    {
        $model = DB::table('tbl_adeudos')
            ->where('prestamo_id', '=', $prestamo_id)
            ->get();

        return $model;
    }

    public function getFolioPrestamoAttribute()
    {
        $folio = HelperCrediuno::get_folio_prestamo($this->prestamo_id);
        return $folio;
    }
}
