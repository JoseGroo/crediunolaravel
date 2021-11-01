<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tbl_log_errors extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'log_error_id';
    protected $hidden = ['log_error_id', 'creado_por', 'fecha_creacion'];

    public static function create(tbl_log_errors $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }
}
