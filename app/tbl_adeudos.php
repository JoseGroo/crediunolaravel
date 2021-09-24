<?php

namespace App;

use App\Enums\estatus_adeudos;
use App\Enums\estatus_prestamo;
use App\Helpers\HelperCrediuno;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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


    #region Objetos de llaves foraneas

    public function tbl_cargos()
    {
        $model = $this->hasMany(tbl_cargos::class, 'adeudo_id', 'adeudo_id');
        return $model->where('activo', '=', true);
    }

    public function getTblCargoAttribute()
    {
        return $this->tbl_cargos->first();
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
            ->where('tbl_adeudos.estatus', '=', estatus_adeudos::Vigente)
            ->get();

        return $model;
    }
}
