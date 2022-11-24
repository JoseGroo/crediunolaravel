<?php

namespace App;

use App\Enums\estatus_descuentos;
use DateTime;
use DB;
use Illuminate\Database\Eloquent\Model;

class tbl_descuentos extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'descuento_id';
    protected $hidden = ['descuento_id', 'cliente_id', 'activo', 'creado_por', 'fecha_creacion'];

    protected $fillable = [
        'descuento_id', 'cliente_id', 'importe', 'fecha_vigencia', 'comentario', 'importe_acreditado'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        self::update_estatus();
    }

    public function tbl_cliente()
    {
        return $this->belongsTo(tbl_clientes::class, 'cliente_id', 'cliente_id');
    }

    public static function create(tbl_descuentos $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_descuentos $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public static function get_vigente_by_cliente_id($cliente_id)
    {
        $model = tbl_descuentos::where([
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', estatus_descuentos::Vigente],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_descuentos::where([
            ['descuento_id', '=', $id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function check_if_exists($cliente_id, $id)
    {
        $model = tbl_descuentos::where([
            ['descuento_id', '!=', $id],
            ['cliente_id', '=', $cliente_id],
            ['estatus', '=', estatus_descuentos::Vigente],
            ['activo', '=', true]
        ])
            ->get()->count();
        return $model;
    }

    private static function update_estatus()
    {
        $affected = DB::table('tbl_descuentos')
            ->where('fecha_vigencia', '<', DB::raw('curdate()'))
            ->update(
                [
                    'estatus' => estatus_descuentos::Vencido
                ]
            );

        return $affected;
    }

    public static function get_pagination($fecha_inicio, $fecha_fin, $estatus, $perPage)
    {
        $model = tbl_descuentos::where([
            ['activo', '=', true],
        ])
            ->where(function($query) use ($fecha_inicio, $fecha_fin, $estatus) {

                if(!empty($estatus)) {
                    $query->where('estatus', '=', $estatus);
                }

                if(!empty($fecha_inicio)) {
                    $fecha_inicio = DateTime::createFromFormat('d/m/Y', $fecha_inicio);
                    $query->whereDate('fecha_vigencia', '>=', $fecha_inicio->format('Y-m-d'));
                }

                if(!empty($fecha_fin)) {
                    $fecha_fin = DateTime::createFromFormat('d/m/Y', $fecha_fin);
                    $query->whereDate('fecha_vigencia', '<=', $fecha_fin->format('Y-m-d'));
                }
            })
            ->orderBy('fecha_vigencia', 'asc')
            ->paginate($perPage);
        return $model;
    }
}
