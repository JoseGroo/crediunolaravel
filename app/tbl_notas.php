<?php

namespace App;

use App\Enums\tipo_nota;
use Illuminate\Database\Eloquent\Model;

class tbl_notas extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'nota_id';
    protected $hidden = ['activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'nota_id', 'nota'
    ];

    public static function create(tbl_notas $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_notas $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function usuario_creo()
    {
        return $this->belongsTo(tbl_usuarios::class, 'creado_por', 'id');
    }


    public static function get_list_by_cliente_id($cliente_id)
    {
        $model = tbl_notas::where([
            ['activo', '=', true],
            ['cliente_id', '=', $cliente_id],
        ])->orderby('fecha_creacion', 'desc')
            ->get();
        return $model;
    }

    public static function get_last_by_cliente_tipo_admin($cliente_id)
    {
        $model = tbl_notas::where([
            ['activo', '=', true],
            ['cliente_id', '=', $cliente_id],
            ['tipo', '=', tipo_nota::Administrador]
        ])->orderby('fecha_creacion', 'desc')
            ->take(2)
            ->get();
        return $model;
    }
}
