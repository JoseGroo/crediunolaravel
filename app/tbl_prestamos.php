<?php

namespace App;

use App\Enums\estatus_adeudos;
use App\Enums\estatus_prestamo;
use App\Enums\periodos_prestamos;
use App\Helpers\HelperCrediuno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class tbl_prestamos extends Model
{
    protected $table = "tbl_prestamos";
    public $timestamps = false;
    protected $primaryKey = 'prestamo_id';


    protected $hidden = [
        'cliente_id', 'interes_id', 'aval_id',
        'garantia_id', 'activo', 'creado_por', 'fecha_creacion', 'usuario_entrego_id'
    ];

    protected $fillable = [
        'prestamo_id', 'cliente_id', 'capital', 'interes_id', 'periodo', 'plazo', 'aval_id', 'garantia_id', 'dia_pago', 'taza_iva',
        'aplico_taza_preferencial', 'capital_concepto', 'manejo_cuenta', 'comision_apertura', 'gastos_papeleria', 'gastos_cobranza', 'seguro_desempleo',
        'fecha_liquidacion', 'usuario_entrego_id', 'dia_descanso', 'dia_pago_manual', 'cobrar_dias_festivos'
    ];

    public static function create(tbl_prestamos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_prestamos $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_id($id)
    {
        $model = tbl_prestamos::where([
            ['prestamo_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_sin_entregar_by_cliente_id($cliente_id)
    {
        $model = tbl_prestamos::where([
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', estatus_prestamo::Generado],
            ['activo', '=', true]
        ])->get();
        return $model;
    }

    public static function get_list_by_cliente_id_and_status($cliente_id, $estatus)
    {
        $model = tbl_prestamos::where([
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', $estatus],
            ['activo', '=', true]
        ])->orderby('prestamo_id', 'desc')
            ->get();
        return $model;
    }

    public static function get_total_by_cliente_id_and_status($cliente_id, $estatus)
    {
        $model = tbl_prestamos::where([
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', $estatus],
            ['activo', '=', true]
        ])->orderby('prestamo_id', 'desc')
            ->count();
        return $model;
    }



    public static function check_if_liquidado_by_prestamo_id($prestamo_id)
    {
        $model = \DB::select("

            SELECT
                (
                IFNULL((
                    SELECT SUM(adeu.importe_total)
                    FROM tbl_adeudos adeu where adeu.prestamo_id = $prestamo_id AND adeu.estatus = 1 AND adeu.activo  = 1
                        ),0)
                )
                +
                (
                IFNULL((
                    SELECT SUM(carg.importe_total)
                    FROM tbl_cargos carg where carg.prestamo_id = $prestamo_id AND carg.estatus = 1 AND carg.activo  = 1
                        ),0)
                ) AS deuda_actual;
        ");

        return collect($model)->first()->deuda_actual <= 0;
    }

    public static function get_list_generados_by_cliente_id($cliente_id)
    {
        $model = tbl_prestamos::where([
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', estatus_prestamo::Vigente],
            ['activo', '=', true]
        ])->get();
        return $model;
    }

    public static function get_ligas_avales_by_cliente_id($cliente_id)
    {
        $query = tbl_prestamos::query()
            ->where([
                ['activo', '=', true],
                ['cliente_id', '=', $cliente_id]
            ])->whereNotNull('aval_id');

        return $query->get();
    }

    public static function get_ligas_avalados_by_aval_id($aval_id)
    {
        $query = tbl_prestamos::query()
            ->where([
                ['activo', '=', true],
                ['aval_id', '=', $aval_id]
            ])->whereNotNull('aval_id');

        return $query->get();
    }

    #region Objetos de llaves foraneas
    public function tbl_interes()
    {
        return $this->belongsTo(tbl_intereses::class, 'interes_id', 'interes_id');
    }

    public function tbl_garantia()
    {
        return $this->belongsTo(tbl_garantias::class, 'garantia_id', 'garantia_id');
    }

    public function tbl_adeudos()
    {
        $model = $this->hasMany(tbl_adeudos::class, 'prestamo_id', 'prestamo_id');
        return $model->where('activo', '=', true)
            //->where('estatus', '=', estatus_adeudos::Vigente)
            ->orderBy('fecha_limite_pago');
    }

    public function tbl_prestamos_reestructurados()
    {
        $model = $this->hasMany(tbl_prestamos_reestructurados::class, 'prestamo_id', 'prestamo_id');
        return $model->where('activo', '=', true)
            ->orderBy('fecha_creacion', 'desc');
    }


    public function tbl_cargos()
    {
        $model = $this->hasMany(tbl_cargos::class, 'prestamo_id', 'prestamo_id');
        return $model->where('tbl_cargos.activo', '=', true)
            ->join('tbl_adeudos as adeu', 'adeu.adeudo_id', '=', 'tbl_cargos.adeudo_id')
            //->where('estatus', '=', estatus_adeudos::Vigente)
            ->orderBy('adeu.fecha_limite_pago')
            ->select([
                'tbl_cargos.importe_total',
                'adeu.numero_pago',
                'adeu.fecha_limite_pago',
                'tbl_cargos.cargo_id',
                'tbl_cargos.estatus'
            ]);
    }

    public function tbl_aval()
    {
        return $this->belongsTo(tbl_avales::class, 'aval_id', 'aval_id');
    }

    public function tbl_cliente()
    {
        return $this->belongsTo(tbl_clientes::class, 'cliente_id', 'cliente_id');
    }
    #endregion

    #region Attributes

    public function getFolioAttribute()
    {
        $folio = HelperCrediuno::get_folio_prestamo($this->prestamo_id);
        return $folio;
    }

    public function getTotalRecibosAttribute()
    {
        $total_recibos = $this->tbl_adeudos->where('estatus', '=', estatus_adeudos::Vigente)->sum('importe_total') + $this->tbl_cargos->where('estatus', '=', estatus_adeudos::Vigente)->sum('tbl_cargo->importe_total');
        return $total_recibos;
    }

    public function getTotalCapitalAttribute()
    {
        $total_recibos = $this->tbl_adeudos->where('estatus', '=', estatus_adeudos::Vigente)->sum('capital');
        return $total_recibos;
    }

    public function getRecibosPendientesAttribute()
    {
        $recibos_pendientes = $this->tbl_adeudos->where('estatus', '=', estatus_adeudos::Vigente)->count();
        return $recibos_pendientes;
    }

    public function getTotalCargosAttribute()
    {
        $total_cargos = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            if($adeudo->tbl_cargo)
                $total_cargos += $adeudo->tbl_cargo->importe_total;
        }

        return $total_cargos;
    }

    public function getTotalAdeudoAttribute()
    {
        return $this->total_cargos + $this->total_recibos;
    }

    public function getNumeroRecibosAttribute()
    {
        return $this->tbl_adeudos->count();
    }

    public function getNumeroCargosAttribute()
    {
        return $this->tbl_cargos->count();
    }

    public function getTotalPagosCargosAttribute()
    {
        return $this->tbl_cargos;
    }

    public function getTotalPagadoCargosAttribute()
    {
        $total_pagado_cargos = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            if($adeudo->tbl_cargo)
                $total_pagado_cargos += $adeudo->tbl_cargo->importe_pagado;
        }

        return $total_pagado_cargos;
    }

    public function getTotalPagadoRecibosAttribute()
    {
        $total_pagado_recibos = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            $total_pagado_recibos += $adeudo->importe_pagado;
        }

        return $total_pagado_recibos;
    }

    public function getTotalPagadoConDescuentoAttribute()
    {
        $total = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            $total += $adeudo->importe_pagado_descuento;
            if($adeudo->tbl_cargo)
                $total += $adeudo->tbl_cargo->importe_pagado_descuento;
        }

        return $total;
    }

    public function getTotalPagosConDescuentoAttribute()
    {
        $total = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            $total += $adeudo->total_pagos_descuento;
            if($adeudo->tbl_cargo)
                $total += $adeudo->tbl_cargo->total_pagos_descuento;
        }

        return $total;
    }

    public function getTotalPagadoRefinanciamientoAttribute()
    {
        $total = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            $total += $adeudo->importe_pagado_refinanciamiento;
            if($adeudo->tbl_cargo)
                $total += $adeudo->tbl_cargo->importe_pagado_refinanciamiento;
        }

        return $total;
    }

    public function getTotalPagosRefinanciamientoAttribute()
    {

        $total = 0;
        foreach ($this->tbl_adeudos as $adeudo)
        {
            $total += $adeudo->total_pagos_refinanciamiento;
            if($adeudo->tbl_cargo)
                $total += $adeudo->tbl_cargo->total_pagos_refinanciamiento;
        }

        return $total;
    }

    public function getDuracionTextAttribute()
    {
        $periodo =  "";
        switch ($this->periodo)
        {
            case periodos_prestamos::Diario:
                $periodo = " Dia(s)";
                break;
            case periodos_prestamos::Semanal:
                $periodo = " Semana(s)";
                break;
            case periodos_prestamos::Quincenal:
                $periodo = " Quincena(s)";
                break;
            case periodos_prestamos::Mensual:
                $periodo = " Mes(es)";
                break;
        }
        return $this->plazo . $periodo;
    }

    #endregion
}
