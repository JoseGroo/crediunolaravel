<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_grupos extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'grupo_id';
    protected $hidden = ['grupo_id', 'activo', 'creado_por', 'fecha_creacion'];


    protected $fillable = [
        'grupo_id', 'grupo'
    ];

    public static function create(tbl_grupos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_grupos $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function tbl_clientes()
    {
        $model = $this->hasMany(tbl_clientes::class, 'grupo_id', 'grupo_id');
        return $model->where('activo', '=', true);
    }

    public static function get_list()
    {
        $model = tbl_grupos::where([
            ['activo', '=', true]
        ])->orderby('grupo_id')
            ->get();
        return $model;
    }

    public static function check_if_exists($grupo, $id)
    {
        $model = tbl_grupos::where([
            ['grupo', '=', $grupo],
            ['grupo_id', '!=', $id]
        ])->get()->count();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_grupos::where([
            ['grupo_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_pagination($grupo, $perPage)
    {

        $model = tbl_grupos::where([
            ['grupo', 'LIKE', '%'.$grupo.'%'],
            ['activo', '=', true]
        ])
            ->orderBy('grupo', 'asc')
            ->paginate($perPage);
        return $model;
    }

    #region Attributes

    public function getTotalClientesAttribute()
    {
        return $this->tbl_clientes->count();
    }
    #endregion
}
