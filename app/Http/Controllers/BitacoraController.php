<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Helpers\HelperCrediuno;
use App\tbl_bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BitacoraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);
        $fecha_actual = HelperCrediuno::get_datetime();
        $fecha_fin = $fecha_actual->format('d/m/Y');
        $fecha_inicio = $fecha_actual->addMonths(-1)->format('d/m/Y');


        $usuario = request('sUsuario') ?? '';
        $fecha_inicio = request('fecha_inicio') ?? $fecha_inicio;
        $fecha_fin = request('fecha_fin') ?? $fecha_fin;
        $catalago_sistema = request('catalago_sistema') ?? '';

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_bitacora::get_pagination($usuario, $fecha_inicio, $fecha_fin, $catalago_sistema,$perPage);
        if($request->ajax()){
            return view('bitacora._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $catalagos_sistema = catalago_sistema::toSelectArray();

        return view('bitacora.index')
            ->with(compact("model"))
            ->with(compact("catalagos_sistema"))
            ->with(compact("fecha_inicio"))
            ->with(compact("fecha_fin"))
            ->with(compact('perPage'));
    }

    public function get_detalles_by_id()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $bitacora_id = request('bitacora_id');
        $bitacora = tbl_bitacora::get_by_id($bitacora_id);

        $view = '';
        $model_anteriores = json_decode($bitacora->datos_anteriores, true);
        $model_actuales = json_decode($bitacora->datos_actuales, true);
        switch ($bitacora->catalago_sistema)
        {
            case catalago_sistema::Usuarios:
                $view = 'bitacora._detalles_usuarios';
                break;
            case catalago_sistema::Sucursales:
                $view = 'bitacora._detalles_sucursales';
                break;
            case catalago_sistema::Grupos:
                $view = 'bitacora._detalles_grupos_cliente';
                break;
            case catalago_sistema::Intereses:
                $view = 'bitacora._detalles_intereses';
                break;
            case catalago_sistema::Fondos:
                $view = 'bitacora._detalles_fondos';
                break;
            case catalago_sistema::DiasFestivos:
                $view = 'bitacora._detalles_dias_festivos';
                break;
            case catalago_sistema::Contactos:
                $view = 'bitacora._detalles_contactos';
                break;
            case catalago_sistema::MediosPublicitarios:
                $view = 'bitacora._detalles_medios_publicitarios';
                break;
        }
        $view_html = view($view)
            ->with(compact($bitacora))
            ->with(compact('model_actuales'))
            ->with(compact('model_anteriores'))
            ->render();
        return Response::json($view_html);
    }
}
