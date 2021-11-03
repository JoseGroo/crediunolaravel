<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\estatus_adeudos;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\CargosManualesRequest;
use App\tbl_cargos;
use App\tbl_cargos_manuales;
use App\tbl_clientes;
use App\tbl_prestamos;
use App\tbl_sucursales;
use Carbon\Carbon;
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

    public function generar_manual_post(CargosManualesRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_cargos_manuales($data_model);

        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_cargos_manuales::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $cargo = tbl_cargos::get_by_adeudo_id($request->adeudo_id);
        $today = Carbon::createFromFormat('Y-m-d', $datetime_now->format('Y-m-d'));
        if($cargo == null)
        {
            $cargo = new tbl_cargos();
            $cargo->adeudo_id = $request->adeudo_id;
            $cargo->interes = $request->importe;
            $cargo->importe_total = $request->importe;
            $cargo->prestamo_id = $request->prestamo_id;
            $cargo->estatus = estatus_adeudos::Vigente;
            $cargo->fecha_ultima_actualizacion = $today;
            $cargo->iva = 0;
            $cargo->activo = true;
            $cargo->fecha_creacion = $datetime_now;
            $response = tbl_cargos::create($cargo);
        }else
        {
            $cargo->interes += $request->importe;
            $cargo->importe_total += $request->importe;
            $cargo->fecha_ultima_actualizacion = $today;
            $cargo->estatus = estatus_adeudos::Vigente;
            $response = tbl_cargos::edit_model($cargo);
        }

        HelperCrediuno::save_bitacora($model->contacto_id, movimiento_bitacora::GeneroCargoManual, $this->catalago_sistema, null, null);
        return redirect()->route('pagos.index');
    }
}
