<?php

namespace App\Helpers;

use App\Enums\estatus_adeudos;
use App\Enums\tipo_intereses;
use App\tbl_adeudos;
use App\tbl_bitacora;
use App\tbl_cargos;
use App\tbl_roles;
use App\tbl_usuarios;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Request;
use Storage;
use Illuminate\Support\Facades\Response;


class HelperCrediuno
{
    public static  $admin_gral_rol = "Administrador general";
    public static  $admin_rol = "Administrador";
    public static  $ventanilla = "Ventanilla";
    public static  $cobranza = "Cobranza";
    public static $nombres_dias = [
        'Monday'    => 'Lunes',
        'Tuesday'   => 'Martes',
        'Wednesday' => 'Miercoles',
        'Thursday'  => 'Jueves',
        'Friday'    => 'Viernes',
        'Saturday'  => 'Sabado',
        'Sunday'    => 'Domingo'
    ];


    public static $path_save_user_profile = "/user_profile/";

    public static function get_rol_by_id($rol_id) {
        $model = tbl_roles::where([
            ['rol_id', '=', $rol_id],
            ['activo', '=', true]
        ])->first();
        return $model;
    }

    public static function save_file($file, $path)
    {
        $filename = 'file-' . time() . '.' . $file->getClientOriginalExtension();
        //$path = $file->storeAs($path, $filename);

        try{
            //$path = $file->move(public_path($path), $filename);
            $path = $file->storeAs($path, $filename);
        }catch (\Exception $exception)
        {
            dd($exception);
        }
        return $path;
        /*return $filename;*/
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

    public static function set_cookie($name, $value){
        $minutes = 1;
        $response = new Response('Set Cookie');
        $response->withCookie(cookie($name, $value, $minutes));
        return $response;
    }

    public static function checar_dia_festivo_y_descanso($dias_festivos, $dia_descanso, $fecha_pago, $cobrar_dias_festivos)
    {
        $correct_date = false;
        $dias_recorridos = 0;
        while (!$correct_date)
        {
            $es_festivo = $dias_festivos->where('fecha', '=', $fecha_pago->format('Y-m-d'))->count() > 0;
            $es_descanso = $fecha_pago->dayOfWeek == $dia_descanso;
            $correct_date = (!$es_festivo && !$cobrar_dias_festivos) && !$es_descanso;

            if(!$correct_date)
            {
                $fecha_pago->addDay();
                $dias_recorridos++;
            }
        }
        $response['dias_recorridos'] = $dias_recorridos;
        $response['nueva_fecha'] = $fecha_pago;
        return $response;
    }

    public static function obtener_fecha_quincenal($fecha_limite_pago)
    {
        $found_day = false;
        while (!$found_day)
        {
            $fecha_limite_pago = $fecha_limite_pago->addDays(1);
            $day = $fecha_limite_pago->format('d');
            if($day == '01' || $day == '16')
            {
                $found_day = true;
            }
        }
        return $fecha_limite_pago;
    }

    public static function generar_cargos()
    {
        $today = Carbon::createFromFormat('Y-m-d', HelperCrediuno::get_datetime()->format('Y-m-d'));
        $full_date_now = HelperCrediuno::get_datetime();

        #region Generar cargos nuevos
        $adeudos = tbl_adeudos::get_list_for_cargos();

        foreach ($adeudos as $item)
        {
            if($item->interes_id == tipo_intereses::Flat || $item->interes_id == tipo_intereses::Micronegocios)
            {
                $fecha_limite_pago = Carbon::createFromFormat('Y-m-d', $item->fecha_limite_pago);
                $total_dias = $today->diffInDays($fecha_limite_pago);

                if($total_dias >= 1)
                {
                    $importe_cargo = (($item->capital_prestamo * $item->interes_diario) / 100) * $total_dias;
                    $iva_cargo = ($importe_cargo * $item->iva_interes) / 100;

                    $importe_total_cargo = $importe_cargo + $iva_cargo;

                    $tbl_cargo = new tbl_cargos();

                    $tbl_cargo->adeudo_id = $item->adeudo_id;
                    $tbl_cargo->interes = $importe_cargo;
                    $tbl_cargo->iva = $iva_cargo;
                    $tbl_cargo->importe_total = $importe_total_cargo;
                    $tbl_cargo->prestamo_id = $item->prestamo_id;
                    $tbl_cargo->estatus = estatus_adeudos::Vigente;
                    $tbl_cargo->fecha_ultima_actualizacion = $today;
                    $tbl_cargo->activo = true;
                    $tbl_cargo->fecha_creacion = $full_date_now;
                    $response = tbl_cargos::create($tbl_cargo);
                }

            }else{
                //dd('NO es flat o micro');
            }
        }
        #endregion

        #region Generar sobre los cargos

        $cargos = tbl_cargos::get_list_for_cargos();

        foreach ($cargos as $item)
        {
            if($item->interes_id == tipo_intereses::Flat || $item->interes_id == tipo_intereses::Micronegocios)
            {
                $fecha_limite_pago = Carbon::createFromFormat('Y-m-d', $item->fecha_ultima_actualizacion);
                $total_dias = $today->diffInDays($fecha_limite_pago);

                if($total_dias >= 1)
                {
                    $cargo_hoy = (($item->capital_prestamo * $item->interes_diario) / 100) * $total_dias;

                    $iva_cargo_hoy = ($cargo_hoy * $item->iva_interes) / 100;

                    $suma_cargo = $cargo_hoy + $item->interes;
                    $suma_iva_cargo = $iva_cargo_hoy + $item->iva;

                    $suma_pago_total = $suma_cargo + $suma_iva_cargo;

                    $response = tbl_cargos::edit($suma_iva_cargo, $suma_cargo, $suma_pago_total, $today, $item->cargo_id);

                }

            }else{
                dd('NO es flat o micro');
            }
        }

        #endregion
    }

}