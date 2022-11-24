<?php

namespace App;

use App\Enums\estatus_movimientos_corte;
use Illuminate\Database\Eloquent\Model;

class tbl_transferencias_fondos extends Model
{
    protected $table = "tbl_transferencias_fondos";
    public $timestamps = false;
    protected $primaryKey = 'transferencia_fondo_id';


    protected $fillable = [
        'transferencia_fondo_id', 'fondo_id', 'corte_id', 'importe', 'divisa_id', 'tipo'
    ];

    public function tbl_divisa()
    {
        return $this->belongsTo(tbl_divisas::class, 'divisa_id', 'divisa_id');
    }

    public function tbl_fondo()
    {
        return $this->belongsTo(tbl_fondos::class, 'fondo_id', 'fondo_id');
    }




    public static function create(tbl_transferencias_fondos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_transferencias_fondos $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_by_id($id)
    {
        $model = tbl_transferencias_fondos::where([
            ['transferencia_fondo_id', '=', $id],
            ['activo', '=', true],
        ])->first();
        return $model;
    }

    public static function get_list_by_corte_id($corte_id, $only_active = false)
    {
        $model = tbl_transferencias_fondos::query()
            ->where([
                ['activo', '=', true],
                ['corte_id', '=', $corte_id]
            ])
            ->orderBy('transferencia_fondo_id', 'desc');

        if($only_active)
            $model->where('estatus', '=', estatus_movimientos_corte::Activo);
        return $model->get();
    }
}
