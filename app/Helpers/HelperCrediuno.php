<?php

namespace App\Helpers;

use App\tbl_bitacora;
use App\tbl_roles;
use App\tbl_usuarios;
use Carbon\Carbon;

class HelperCrediuno
{
    public static  $admin_gral_rol = "Administrador general";
    public static  $admin_rol = "Administrador";
    public static  $ventanilla = "Ventanilla";
    public static  $cobranza = "Cobranza";

    public static function get_rol_by_id($rol_id) {
        $model = tbl_roles::where([
            ['rol_id', '=', $rol_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function save_file($file, $path)
    {
        $filename = 'profile-photo-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($path, $filename);

        return $path;
    }

    public static function authorizeRoles($roles)
    {
        $auth = new tbl_usuarios();
        $auth->authorizeRoles($roles);
    }

    public static function save_bitacora($registro_id, $movimiento, $catalago_sistema, $json_datos_anteriores, $json_datos_actuales)
    {
        $bitacora = new tbl_bitacora();
        $bitacora->activo = true;
        $bitacora->usuario_id = auth()->user()->id;
        $bitacora->fecha = HelperCrediuno::get_datetime();
        $bitacora->movimiento = $movimiento;
        $bitacora->datos_anteriores = $json_datos_anteriores;
        $bitacora->datos_actuales = $json_datos_actuales;
        $bitacora->catalago_sistema = $catalago_sistema;
        $bitacora->registro_id = $registro_id;

        return tbl_bitacora::create($bitacora);
    }

    public static function get_datetime()
    {
        $datetime_now = Carbon::now()->setTimezone('GMT-7');
        return $datetime_now;
    }

    public static function ActiveMenuControllers($controllers)
    {
        return in_array(request()->segment(1), $controllers) ? 'active' : '';
    }
}