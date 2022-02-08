<?php

namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_clientes extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'cliente_id';
    protected $hidden = [
        'sucursal_id', 'medio_publicitario_id', 'grupo_id',
         'estado_id', 'activo', 'creado_por', 'fecha_creacion', 'ciudad', 'estado'
    ];

    protected $fillable = [
        'cliente_id', 'sucursal_id', 'medio_publicitario_id', 'grupo_id', 'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento',
        'sexo', 'estado_civil', 'ocupacion', 'calle', 'numero_exterior', 'numero_interior', 'colonia', 'entre_calles', 'senas_particulares',
        'pais', 'estado_id', 'localidad', 'codigo_postal', 'tiempo_residencia', 'unidad_tiempo_residencia', 'vivienda', 'renta', 'estatus',
        'mostrar_cobranza', 'limite_credito'
    ];

    public static function create(tbl_clientes $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_clientes $model)
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

    public function medio_publicitario()
    {
        return $this->belongsTo(tbl_medios_publicitarios::class, 'medio_publicitario_id', 'medio_publicitario_id');
    }

    public function grupo()
    {
        return $this->belongsTo(tbl_grupos::class, 'grupo_id', 'grupo_id');
    }

    public function estado()
    {
        return $this->belongsTo(tbl_estados::class, 'estado_id', 'estado_id');
    }

    public function tbl_conyuge()
    {
        return $this->belongsTo(tbl_conyuge_cliente::class, 'cliente_id', 'cliente_id');
    }

    public function tbl_documentos()
    {
        $model = $this->hasMany(tbl_documentos_cliente::class, 'cliente_id', 'cliente_id');
        return $model->where('activo', '=', true);
    }

    public function tbl_informacion_contacto()
    {
        return $this->belongsTo(tbl_informacion_contacto_cliente::class, 'cliente_id', 'cliente_id');
    }

    public function tbl_informacion_laboral()
    {
        return $this->belongsTo(tbl_informacion_laboral_cliente::class, 'cliente_id', 'cliente_id');
    }

    public function tbl_economia()
    {
        return $this->belongsTo(tbl_economia_cliente::class, 'cliente_id', 'cliente_id');
    }
    #endregion

    public static function check_if_exists($nombre, $apellido_paterno, $apellido_materno, $id)
    {
        $model = tbl_clientes::where([
            ['nombre', '=', $nombre],
            ['apellido_paterno', '=', $apellido_paterno],
            ['apellido_materno', '=', $apellido_materno],
            ['cliente_id', '!=', $id]
        ])->get()->count();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_clientes::where([
            ['cliente_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list_by_search($cliente)
    {
        $model = tbl_clientes::where([
            ['activo', '=', true]
        ])
            ->where(DB::raw("CONCAT(cliente_id, '-', COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,''))"), 'LIKE', "%".$cliente."%")
            ->select([
                DB::raw("CONCAT(cliente_id, '-', COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,'')) AS lavel"),
                DB::raw("CONCAT(cliente_id, '-', COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,'')) AS value"),
                "cliente_id"
            ])
            ->take(10)
            ->orderBy(DB::raw("CONCAT(COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,''))"), 'ASC')
            ->get()
            ->toArray();
        return $model;
    }

    public static function get_list_by_search_html($cliente)
    {
        $model = tbl_clientes::where([
            ['activo', '=', true]
        ])
            ->where(DB::raw("CONCAT(cliente_id, '-', COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,''))"), 'LIKE', "%".$cliente."%")
            ->select("*")
            ->take(50)
            ->orderBy(DB::raw("CONCAT(COALESCE(nombre,''), ' ', COALESCE(apellido_paterno,''), ' ', COALESCE(apellido_materno,''))"), 'ASC')
            ->get();
        return $model;
    }

    public static function get_pagination($nombre, $sucursal_id, $domicilio, $perPage)
    {
        DB::enableQueryLog();
        $model = DB::table('tbl_clientes AS cli')
            ->where('cli.activo', true)
            ->where(DB::raw("CONCAT(COALESCE(cli.nombre,''), ' ', COALESCE(cli.apellido_paterno,''), ' ', COALESCE(cli.apellido_materno,''))"), 'LIKE', "%".$nombre."%")
            ->where(DB::raw("CONCAT(COALESCE(cli.localidad,''), ' ', COALESCE(cli.calle,''), ' ', COALESCE(cli.colonia,''))"), 'LIKE', "%".$domicilio."%")
            ->where(function($query) use ($sucursal_id) {

                if($sucursal_id > 0)
                    $query->where('cli.sucursal_id', $sucursal_id);

            })
            ->join('tbl_sucursales as suc', 'suc.sucursal_id', '=', 'cli.sucursal_id')
            ->join('tbl_estados as est', 'est.estado_id', '=', 'cli.estado_id')
            ->leftJoin('tbl_informacion_contacto_cliente as inf', 'inf.cliente_id', '=', 'cli.cliente_id')
            ->orderBy('cli.nombre', 'asc')
            ->orderBy('cli.apellido_paterno', 'asc')
            ->orderBy('cli.apellido_materno', 'asc')
            ->select([
                    "cli.cliente_id",
                    "cli.es_aval",
                    "cli.nombre",
                    "cli.apellido_paterno",
                    "cli.apellido_materno",
                    "cli.foto",
                    "est.estado",
                    "cli.estatus",
                    "cli.localidad",
                    "cli.colonia",
                    "cli.calle",
                    "suc.sucursal",
                    "inf.telefono_fijo",
                    "inf.telefono_movil",
                    DB::raw("(
                        SELECT SUM(adeu.importe_total) + SUM(carg.importe_total)
                        FROM tbl_prestamos prest
                        LEFT JOIN tbl_adeudos adeu ON adeu.prestamo_id = prest.prestamo_id AND adeu.activo = 1 AND adeu.estatus = 1
                        LEFT JOIN tbl_cargos carg ON carg.adeudo_id = adeu.adeudo_id AND carg.activo = 1 AND carg.estatus = 1
                        WHERE prest.activo = 1 AND prest.cliente_id = cli.cliente_id AND prest.estatus = 2
                    ) AS total_deuda")
                    ])
            ->paginate($perPage);
        //dd(DB::getQueryLog());
        return $model;
    }

    public static function get_clientes_pagination($grupo_id, $id, $nombre, $perPage)
    {

        $model = tbl_clientes::where([
            ['cliente_id', 'LIKE', '%'.$id.'%'],
            ['activo', '=', true]
        ])
            ->where(DB::raw("CONCAT(COALESCE(tbl_clientes.nombre,''), ' ', COALESCE(tbl_clientes.apellido_paterno,''), ' ', COALESCE(tbl_clientes.apellido_materno,''))"), 'LIKE', "%".$nombre."%")
            ->orderBy('sucursal', 'asc')
            ->paginate($perPage);
        return $model;
    }

    #region Attributes

    public function getFullNameAttribute()
    {
       return $this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }
    #endregion
}
