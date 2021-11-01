<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\tbl_cargos;
use App\tbl_cargos_manuales;
use App\tbl_clientes;
use App\tbl_prestamos;
use App\tbl_sucursales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Lang;

class PagosController extends Controller
{
    private $catalago_sistema = catalago_sistema::Adeudos;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $cliente = $request->cliente;

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_cargos_manuales::get_pagination($fecha_inicio, $fecha_fin, $cliente, $perPage);
        if($request->ajax()){
            return view('pagos._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        return view('pagos.index')
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function eliminar_cargos(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $cliente_id = $request->cliente_id;

        $model = tbl_prestamos::get_list_generados_by_cliente_id($cliente_id);

        if($request->ajax()){
            return view('pagos._eliminar_cargos')
                ->with(compact("model"));
        }

        return view('pagos.eliminar_cargos')
            ->with(compact("model"));
    }

    public function eliminar_cargos_post(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $model = tbl_cargos::get_by_id($request->cargo_id);
            $model->activo = false;

            $response = tbl_cargos::edit_model($model);

            if($response['saved'] == false)
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->cargo_id, movimiento_bitacora::Elimino, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved' => true,
                'Message'   => 'Se elimino correctamente el cargo.'
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => 'Ocurrio un error al intentar guardar la información.'
        ));
    }

    public function generar_manual()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        return view('pagos.generar_cargo');
    }
}
