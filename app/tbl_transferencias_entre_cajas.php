<?php

namespace App;

use App\Enums\estatus_movimientos_corte;
use Illuminate\Database\Eloquent\Model;

class tbl_transferencias_entre_cajas extends Model
{
    protected $table = "tbl_transferencias_entre_cajas";
    public $timestamps = false;
    protected $primaryKey = 'transferencia_entre_caja_id';

    protected $fillable = [
        'transferencia_entre_caja_id', 'corte_origen_id', 'corte_destino_id', 'importe', 'divisa_id'
    ];

    public function tbl_divisa()
    {
        return $this->belongsTo(tbl_divisas::class, 'divisa_id', 'divisa_id');
    }

    public function tbl_corte_origen()
    {
        return $this->belongsTo(tbl_cortes::class, 'corte_origen_id', 'corte_id');
    }

    public function tbl_corte_destino()
    {
        return $this->belongsTo(tbl_cortes::class, 'corte_destino_id', 'corte_id');
    }


    public static function create(tbl_transferencias_entre_cajas $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_transferencias_entre_cajas $model)
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
        $model = tbl_transferencias_entre_cajas::where([
            ['transferencia_entre_caja_id', '=', $id],
            ['activo', '=', true],
        ])->first();
        return $model;
    }

    public static function get_list_by_corte_id($corte_id, $only_active = false)
    {
        $model = tbl_transferencias_entre_cajas::query()
            ->where([
                ['activo', '=', true]
            ])
            ->where(function($query) use ($corte_id){
                $query->where('corte_origen_id', '=', $corte_id)
                    ->orWhere('corte_destino_id', '=', $corte_id);
            })
            ->orderBy('transferencia_entre_caja_id', 'desc');

        if($only_active)
            $model->where('estatus', '=', estatus_movimientos_corte::Activo);

        return $model->get();
    }
}
