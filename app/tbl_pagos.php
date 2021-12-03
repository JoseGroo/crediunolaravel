<?php

namespace App;

use App\Enums\tipo_adeudo;
use App\Enums\tipo_pago;
use Illuminate\Database\Eloquent\Model;

class tbl_pagos extends Model
{
    protected $table = "tbl_pagos";
    public $timestamps = false;
    protected $primaryKey = 'pago_id';


    protected $hidden = [
        'pago_id', 'activo', 'creado_por', 'fecha_creacion'
    ];

    protected $fillable = [
        'pago_id', 'importe', 'external_id', 'tipo', 'descuento_id', 'capital', 'interes', 'iva', 'cambio', 'estatus'
    ];

    public static function create(tbl_pagos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_pagos $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_list_by_ids($ids)
    {
        $model = tbl_pagos::where([
            ['activo', '=', true]
        ])->whereIn('pago_id', $ids)
            ->orderby('pago_id')
            ->get();
        return $model;
    }

    public function tbl_adeudo()
    {
        $model = $this->belongsTo(tbl_adeudos::class, 'external_id', 'adeudo_id');
        return $this->tipo == tipo_pago::Adeudo ? $model : null;
    }

    public function tbl_cargo()
    {
        $model = $this->belongsTo(tbl_cargos::class, 'external_id', 'cargo_id');
        return $this->tipo == tipo_pago::Cargo ? $model : null;
    }

    public function getPrestamoIdAttribute()
    {
        $value = $this->tipo == tipo_pago::Adeudo ? $this->tbl_adeudo->prestamo_id : $this->tbl_cargo->prestamo_id;
        return $value;
    }

    public function getNumeroPagoAttribute()
    {
        $value = $this->tipo == tipo_pago::Adeudo ? $this->tbl_adeudo->numero_pago : $this->tbl_cargo->tbl_adeudo->numero_pago;
        return $value;
    }
}
