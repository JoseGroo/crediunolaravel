<?php

namespace App;

use App\Enums\unidad_tiempo;
use Illuminate\Database\Eloquent\Model;

class tbl_referencias_cliente extends Model
{
    protected $table = 'tbl_referencias_cliente';
    public $timestamps = false;
    protected $primaryKey = 'referencia_cliente_id';
    protected $hidden = ['referencia_cliente_id', 'cliente_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'referencia_cliente_id', 'cliente_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'telefono_fijo', 'telefono_movil',
        'telefono_oficina','correo_electronico','calle','numero_exterior','colonia','tiempo_conocerlo','unidad_tiempo_conocerlo','relacion'
    ];

    public static function create(tbl_referencias_cliente $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_referencias_cliente $model)
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
        $model = tbl_referencias_cliente::where([
            ['referencia_cliente_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_by_cliente_id($cliente_id)
    {
        $model = tbl_referencias_cliente::where([
            ['activo', '=', true],
            ['cliente_id', '=', $cliente_id]
        ])->orderby('fecha_creacion', 'desc')
            ->get();
        return $model;
    }
    public function getTiempoConocerloCompletoAttribute()
    {

        return $this->tiempo_conocerlo. ' ' .unidad_tiempo::getDescription($this->unidad_tiempo_conocerlo);
    }
}
