<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class tbl_garantias extends Model
{
    protected $table = "tbl_garantias";
    public $timestamps = false;
    protected $primaryKey = 'garantia_id';


    protected $hidden = [
        'garantia_id'
    ];

    protected $fillable = [
        'garantia_id', 'tipo', 'descripcion', 'valor', 'cliente_id'
    ];

    public static function create(tbl_garantias $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_garantias $model)
    {
        try{

            $model->update();
            return ['saved' => true, 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_list_by_cliente_id($cliente_id)
    {
        $model = tbl_garantias::where([
            ['cliente_id', '=', $cliente_id],
            ['activo', '=', true]
        ])->get();
        return $model;
    }


    #region Attributes

    public function getFullNameAttribute()
    {
        $value_length = Str::length($this->descripcion);
        $short_description = Str::substr($this->descripcion, 0, $value_length > 40 ? 40 : $value_length);

        return $short_description;
    }
    #endregion
}
