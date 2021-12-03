<?php

namespace App;

use App\Enums\estatus_adeudos;
use App\Enums\estatus_prestamo;
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

    public static function get_list_vigentes_by_cliente_id($cliente_id)
    {
        $model = tbl_prestamos::where([
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', estatus_prestamo::Vigente],
            ['activo', '=', true]
        ])->get();
        return $model;
    }



    public static function check_if_liquidado_by_prestamo_id($prestamo_id)
    {
        $model = \DB::select("

            SELECT
                (
                IFNULL((
                    SELECT SUM(adeu.importe_total)
                    FROM tbl_adeudos adeu where adeu.prestamo_id = $prestamo_id AND adeu.estatus = 1
                        ),0)
                )
                +
                (
                IFNULL((
                    SELECT SUM(carg.importe_total)
                    FROM tbl_cargos carg where carg.prestamo_id = $prestamo_id AND carg.estatus = 1
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
                'tbl_cargos.cargo_id'
            ]);
    }

    public function tbl_aval()
    {
        return $this->belongsTo(tbl_avales::class, 'aval_id', 'aval_id');
    }
    #endregion



    #region Attributes

    public function getFolioAttribute()
    {
        $id_length = Str::length($this->prestamo_id);

        switch ($id_length)
        {
            case 1:
                $folio = '000000';
                break;
            case 2:
                $folio = '00000';
                break;
            case 3:
                $folio = '0000';
                break;
            case 4:
                $folio = '000';
                break;
            case 5:
                $folio = '00';
                break;
            case 6:
                $folio = '0';
                break;
            default:
                $folio = '';
                break;
        }
        return $folio . $this->prestamo_id;
    }

    public function getTotalRecibosAttribute()
    {
        $total_recibos = $this->tbl_adeudos->sum('importe_total') + $this->tbl_adeudos->sum('tbl_cargo->importe_total');
        return $total_recibos;
    }

    public function getRecibosPendientesAttribute()
    {
        $recibos_pendientes = $this->tbl_adeudos->count();
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



    #endregion
}
