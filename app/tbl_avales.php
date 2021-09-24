<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_avales extends Model
{
    protected $table = "tbl_avales";
    public $timestamps = false;
    protected $primaryKey = 'aval_id';
    protected $hidden = [
        'aval_id', 'sucursal_id', 'estado_id', 'activo', 'creado_por', 'fecha_creacion', 'ciudad', 'estado','cliente_id'
    ];

    protected $fillable = [
        'aval_id', 'sucursal_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento',
        'sexo', 'estado_civil', 'ocupacion', 'calle', 'numero_exterior', 'numero_interior', 'colonia', 'entre_calles', 'senas_particulares',
        'pais', 'estado_id', 'localidad', 'codigo_postal', 'tiempo_residencia', 'unidad_tiempo_residencia','es_cliente','cliente_id'
    ];

    public static function create(tbl_avales $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_avales $model)
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
    public function sucursal()
    {
        return $this->belongsTo(tbl_sucursales::class, 'sucursal_id', 'sucursal_id');
    }

    public function estado()
    {
        return $this->belongsTo(tbl_estados::class, 'estado_id', 'estado_id');
    }

    public function tbl_conyuge()
    {
        return $this->belongsTo(tbl_conyuge_aval::class, 'aval_id', 'aval_id');
    }

    public function tbl_documentos()
    {
        $model = $this->hasMany(tbl_documentos_aval::class, 'aval_id', 'aval_id');
        return $model->where('activo', '=', true);
    }

    public function tbl_informacion_contacto()
    {
        return $this->belongsTo(tbl_informacion_contacto_aval::class, 'aval_id', 'aval_id');
    }

    public function tbl_informacion_laboral()
    {
        return $this->belongsTo(tbl_informacion_laboral_aval::class, 'aval_id', 'aval_id');
    }

    public function tbl_economia()
    {
        return $this->belongsTo(tbl_economia_aval::class, 'aval_id', 'aval_id');
    }

    #endregion

    public static function get_by_id($id)
    {
        $model = tbl_avales::where([
            ['aval_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function check_if_exists($nombre, $apellido_paterno, $apellido_materno, $id)
    {
        $model = tbl_avales::where([
            ['nombre', '=', $nombre],
            ['apellido_paterno', '=', $apellido_paterno],
            ['apellido_materno', '=', $apellido_materno],
            ['aval_id', '!=', $id]
        ])->get()->count();
        return $model;
    }

    public static function get_list_by_name($nombre)
    {
        $model = tbl_avales::where(DB::raw("CONCAT(COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,''))"), 'LIKE', "%".$nombre."%")
            ->where('activo', '=', true)
            ->orderBy('nombre', 'asc')
            ->orderBy('apellido_paterno', 'asc')
            ->orderBy('apellido_materno', 'asc')
            ->take(10)
            ->get();
        return $model;
    }

    public static function get_pagination($nombre, $sucursal_id, $domicilio, $perPage)
    {
        DB::enableQueryLog();
        $model = DB::table('tbl_avales AS aval')
            ->where('aval.activo', true)
            ->where(DB::raw("CONCAT(COALESCE(aval.nombre,''), ' ', COALESCE(aval.apellido_paterno,''), ' ', COALESCE(aval.apellido_materno,''))"), 'LIKE', "%".$nombre."%")
            ->where(DB::raw("CONCAT(COALESCE(aval.localidad,''), ' ', COALESCE(aval.calle,''), ' ', COALESCE(aval.colonia,''))"), 'LIKE', "%".$domicilio."%")
            ->where(function($query) use ($sucursal_id) {

                if($sucursal_id > 0)
                    $query->where('aval.sucursal_id', $sucursal_id);

            })
            ->join('tbl_sucursales as suc', 'suc.sucursal_id', '=', 'aval.sucursal_id')
            ->join('tbl_estados as est', 'est.estado_id', '=', 'aval.estado_id')
            ->leftJoin('tbl_informacion_contacto_aval as inf', 'inf.aval_id', '=', 'aval.aval_id')
            ->orderBy('aval.nombre', 'asc')
            ->orderBy('aval.apellido_paterno', 'asc')
            ->orderBy('aval.apellido_materno', 'asc')
            ->select([
                "aval.aval_id",
                "aval.es_cliente",
                "aval.nombre",
                "aval.apellido_paterno",
                "aval.apellido_materno",
                "aval.foto",
                "est.estado",
                "aval.localidad",
                "aval.colonia",
                "aval.calle",
                "suc.sucursal",
                "inf.telefono_fijo",
                "inf.telefono_movil"
            ])
            ->paginate($perPage);
        //dd(DB::getQueryLog());
        return $model;
    }

    #region Attributes

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }
    #endregion
}
