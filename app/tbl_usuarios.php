<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class tbl_usuarios extends Authenticatable
{
    //protected $table = 'tbl_usuarios';
    protected $hidden = ['id', 'password', 'sucursal_id', 'rol_id', 'remember_token', 'activo', 'creado_por', 'fecha_creacion', 'rol', 'sucursal'];

    public $timestamps = false;

    protected $fillable = [
        'id', 'nombre', 'apellido_paterno', 'apellido_materno', 'direccion', 'telefono', 'seguro_social', 'foto', 'sucursal_id', 'usuario', 'password', 'rol_id'
    ];

    //region User role authenticate
    public function authorizeRoles($roles)
    {
        abort_unless($this->hasAnyRole($roles), 401);
        return true;
    }
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($role)
    {
        $rol_id = auth()->user()->rol_id;
        if (tbl_roles::get_by_name($role)->rol_id == $rol_id) {
            return true;
        }
        return false;
    }
    //endregion

    public static function create(tbl_usuarios $model)
    {
        try{
            return ['saved' => $model->save(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e->getMessage()];
        }
    }

    public static function edit(tbl_usuarios $model)
    {
        try{
            return ['saved' => $model->update(), 'error' => ''];
        }
        catch(\Exception $e){
            return ['saved' => false, 'error' => $e];
        }
    }

    public function rol()
    {
        return $this->belongsTo(tbl_roles::class, 'rol_id', 'rol_id');
    }

    public function tbl_cortes()
    {
        $model = $this->hasMany(tbl_cortes::class, 'usuario_id', 'id')->where('cerrado', false);
        return $model;
    }

    public function getTblCorteAttribute()
    {
        return $this->tbl_cortes->first();
    }


    public function sucursal()
    {
        return $this->belongsTo(tbl_sucursales::class, 'sucursal_id', 'sucursal_id');
    }

    public static function check_if_exists($usuario, $id)
    {
        $model = tbl_usuarios::where([
            ['usuario', '=', $usuario],
            ['id', '!=', $id]
        ])->get()->count();
        return $model;
    }

    public static function get_by_id($id)
    {
        $model = tbl_usuarios::where([
            ['id', '=', $id]
        ])->first();
        return $model;
    }

    public static function get_by_name($nombre)
    {
        $model = tbl_usuarios::where([
            ['nombre', 'LIKE', '%'.$nombre.'%'],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function get_list()
    {
        $model = tbl_usuarios::where([
            ['activo', '=', true]
        ])->get();
        return $model;
    }

    public static function get_pagination($nombre, $perPage)
    {

        $model = DB::table('tbl_usuarios AS usu')
            /*->where([
                ['nombre', 'LIKE', '%'.$nombre.'%']
            ])*/
            ->where(DB::raw("CONCAT(COALESCE(usu.nombre,''), ' ', COALESCE(usu.apellido_paterno,''), ' ', COALESCE(usu.apellido_materno,''))"), 'LIKE', "%".$nombre."%")
            ->join('tbl_roles as rol', 'usu.rol_id', '=', 'rol.rol_id')
            ->orderBy('usu.activo', 'desc')
            ->orderBy('usu.rol_id', 'asc')
            ->orderBy('usu.nombre', 'asc')
            ->orderBy('usu.apellido_paterno', 'asc')
            ->orderBy('usu.apellido_materno', 'asc')
            ->select('usu.id', 'usu.nombre', 'usu.apellido_paterno', 'usu.apellido_materno', 'usu.usuario', 'rol.rol', 'usu.activo', 'usu.foto')
            ->paginate($perPage);
        return $model;
    }

    public function getTieneCorteAbiertoAttribute()
    {
        return $this->tbl_cortes->count() > 0;
    }

    public function getCorteIdAttribute()
    {
        return $this->tbl_cortes->first()->corte_id;
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre.' '.$this->apellido_paterno;
    }


    //use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*protected $hidden = [
        'password', 'remember_token',
    ];*/

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    /*protected $casts = [
        'email_verified_at' => 'datetime',
    ];*/
}
