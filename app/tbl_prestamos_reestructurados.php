<?php

namespace App;

use App\Enums\periodos_prestamos;
use Illuminate\Database\Eloquent\Model;

class tbl_prestamos_reestructurados extends Model
{
    protected $table = "tbl_prestamos_reestructurados";
    public $timestamps = false;
    protected $primaryKey = 'prestamo_reestructurado_id';

    public static function create(tbl_prestamos_reestructurados $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_prestamos_reestructurados $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
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
        return $this->total_pagos . $periodo;
    }
}
