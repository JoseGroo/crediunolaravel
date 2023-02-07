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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
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

    public static function generate_pdf($data, $view, $file_name)
    {
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = \PDF::setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,"DOMPDF_ENABLE_REMOTE" => true])->loadView($view, $data);
        $pdf->getDomPDF()->setHttpContext($contxt);
        return $pdf->download($file_name. '-'. time() .'.pdf');
    }

    public static function setCookie($name, $value, $minutes = 5){
        $cookie = Cookie::make($name, $value, $minutes);
        return $cookie;
    }

    public static function getCookie($name){
        $value = Cookie::get($name);
        return $value;
    }

    public static function deleteCookie($name){
        return Cookie::forget($name);
    }

    public static function convertir_numero_a_letra($num, $fem = false, $dec = true) {
        set_time_limit(900000);
        $matuni[2]  = "DOS";
        $matuni[3]  = "TRES";
        $matuni[4]  = "CUATRO";
        $matuni[5]  = "CINCO";
        $matuni[6]  = "SEIS";
        $matuni[7]  = "SIETE";
        $matuni[8]  = "OCHO";
        $matuni[9]  = "NUEVE";
        $matuni[10] = "DIEZ";
        $matuni[11] = "ONCE";
        $matuni[12] = "DOCE";
        $matuni[13] = "TRECE";
        $matuni[14] = "CATORCE";
        $matuni[15] = "QUINCE";
        $matuni[16] = "DIECISEIS";
        $matuni[17] = "DIECISIETE";
        $matuni[18] = "DIECIOCHO";
        $matuni[19] = "DIECINUEVE";
        $matuni[20] = "VEINTE";
        $matunisub[2] = "DOS";
        $matunisub[3] = "TRES";
        $matunisub[4] = "CUATRO";
        $matunisub[5] = "QUIN";
        $matunisub[6] = "SEIS";
        $matunisub[7] = "SETE";
        $matunisub[8] = "OCHO";
        $matunisub[9] = "NOVE";

        $matdec[2] = "VEINT";
        $matdec[3] = "TREINTA";
        $matdec[4] = "CUARENTA";
        $matdec[5] = "CINCUENTA";
        $matdec[6] = "SESENTA";
        $matdec[7] = "SETENTA";
        $matdec[8] = "OCHENTA";
        $matdec[9] = "NOVENTA";
        $matsub[3]  = 'MILL';
        $matsub[5]  = 'BILL';
        $matsub[7]  = 'MILL';
        $matsub[9]  = 'TRILL';
        $matsub[11] = 'MILL';
        $matsub[13] = 'BILL';
        $matsub[15] = 'MILL';
        $matmil[4]  = 'MILLONES';
        $matmil[6]  = 'BILLONES';
        $matmil[7]  = 'DE BILLONES';
        $matmil[8]  = 'MILLONES DE BILLONES';
        $matmil[10] = 'TRILLONES';
        $matmil[11] = 'DE TRILLONES';
        $matmil[12] = 'MILLONES DE TRILLONES';
        $matmil[13] = 'DE TRILLONES';
        $matmil[14] = 'BILLONES DE TRILLONES';
        $matmil[15] = 'DE BILLONES DE TRILLONES';
        $matmil[16] = 'MILLONES DE BILLONES DE TRILLONES';
        $num;

        //Zi hack
        $float=explode('.',$num);
        $num=$float[0];

        $num = trim((string)@$num);
        if ($num[0] == '-') {
            $neg = 'menos ';
            $num = substr($num, 1);
        }else
            $neg = '';
        while ($num[0] == '0') $num = substr($num, 1);
        if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
        $zeros = true;
        $punt = false;
        $ent = '';
        $fra = '';
        for ($c = 0; $c < strlen($num); $c++) {
            $n = $num[$c];
            if (! (strpos(".,'''", $n) === false)) {
                if ($punt) break;
                else{
                    $punt = true;
                    continue;
                }

            }elseif (! (strpos('0123456789', $n) === false)) {
                if ($punt) {
                    if ($n != '0') $zeros = false;
                    $fra .= $n;
                }else

                    $ent .= $n;
            }else

                break;

        }
        $ent = '     ' . $ent;
        if ($dec and $fra and ! $zeros) {
            $fin = ' coma';
            for ($n = 0; $n < strlen($fra); $n++) {
                if (($s = $fra[$n]) == '0')
                    $fin .= ' CERO';
                elseif ($s == '1')
                    $fin .= $fem ? ' UNA' : ' UN';
                else
                    $fin .= ' ' . $matuni[$s];
            }
        }else
            $fin = '';
        if ((int)$ent === 0) return 'CERO ' . $fin;
        $tex = '';
        $sub = 0;
        $mils = 0;
        $neutro = false;
        while ( ($num = substr($ent, -3)) != '   ') {
            $ent = substr($ent, 0, -3);
            if (++$sub < 3 and $fem) {
                $matuni[1] = 'UNA';
                $subcent = 'AS';
            }else{
                $matuni[1] = $neutro ? 'UN' : 'UNO';
                $subcent = 'OS';
            }
            $t = '';
            $n2 = substr($num, 1);
            if ($n2 == '00') {
            }elseif ($n2 < 21)
                $t = ' ' . $matuni[(int)$n2];
            elseif ($n2 < 30) {
                $n3 = $num[2];
                if ($n3 != 0) $t = 'I' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }else{
                $n3 = $num[2];
                if ($n3 != 0) $t = ' Y ' . $matuni[$n3];
                $n2 = $num[1];
                $t = ' ' . $matdec[$n2] . $t;
            }
            $n = $num[0];
            if ($n == 1) {
                $t = ' CIENTO' . $t;
            }elseif ($n == 5){
                $t = ' ' . $matunisub[$n] . 'IENT' . $subcent . $t;
            }elseif ($n != 0){
                $t = ' ' . $matunisub[$n] . 'CIENT' . $subcent . $t;
            }
            if ($sub == 1) {
            }elseif (! isset($matsub[$sub])) {
                if ($num == 1) {
                    $t = ' MIL';
                }elseif ($num > 1){
                    $t .= ' MIL';
                }
            }elseif ($num == 1) {
                $t .= ' ' . $matsub[$sub] . '?n';
            }elseif ($num > 1){
                $t .= ' ' . $matsub[$sub] . 'ONES';
            }
            if ($num == '000') $mils ++;
            elseif ($mils != 0) {
                if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
                $mils = 0;
            }
            $neutro = true;
            $tex = $t . $tex;
        }
        $tex = $neg . substr($tex, 1) . $fin;
        //Zi hack --> return ucfirst($tex);
        $end_num=ucfirst($tex).' PESOS 00/100 M. N.';
        return $end_num;
    }

    public static function get_folio_prestamo($id)
    {
        $id_length = Str::length($id);
        $ceros = '00000';
        $folio = Str::substr($ceros, 0,Str::length($ceros) - $id_length);

        return $folio . $id;
    }

    public static function convert_url_to_base64($url)
    {
        $path = $url;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = \File::get($path);

        $base64 = "";
        if ($type == "svg") {
            $base64 = "data:image/svg+xml;base64,".base64_encode($data);
        } else {
            $base64 = "data:image/". $type .";base64,".base64_encode($data);
        }
        return $base64;
    }
}
