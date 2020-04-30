<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Enums\tipo_fondo;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\FondosRequest;
use App\tbl_fondos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FondosController extends Controller
{
    private $catalago_sistema = catalago_sistema::Fondos;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $fondo = request('sFondo');
        $tipo_fondo = request('tipo_fondo');

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_fondos::get_pagination($fondo, $tipo_fondo, $perPage);
        if($request->ajax()){
            return view('fondos._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $tipos_fondo = tipo_fondo::toSelectArray();

        return view('fondos.index')
            ->with(compact("model"))
            ->with(compact("tipos_fondo"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $tipos_fondos = tipo_fondo::getInstances();

        return view('fondos.create')
            ->with(compact('tipos_fondos'));
    }

    public function create_post(FondosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_fondos::check_if_exists(request('fondo'), 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otro');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_fondos($data_model);
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_fondos::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->fondo_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('fondos.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_fondos::get_by_id($id);
        if(!$model || !$model->activo)
            abort(404);

        $tipos_fondos = tipo_fondo::getInstances();

        return view('fondos.edit')
            ->with(compact('model'))
            ->with(compact('tipos_fondos'));
    }

    public function edit_post(FondosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('fondo_id');
        $model_original = tbl_fondos::get_by_id($id);
        $model_anterior = tbl_fondos::get_by_id($id);

        if(!$model_original || !$model_original->activo)
            abort(404);

        if(tbl_fondos::check_if_exists(request('fondo'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otro');
        }

        $model_original->fondo = request('fondo');
        $model_original->tipo = request('tipo');
        $model_original->banco = request('banco');
        $model_original->cuenta = request('cuenta');
        $model_original->ultimo_cheque_girado = request('ultimo_cheque_girado');
        $model_original->importe_pesos = request('importe_pesos');
        $model_original->importe_dolares = request('importe_dolares');
        $model_original->importe_dolares_moneda = request('importe_dolares_moneda');
        $model_original->importe_euros = request('importe_euros');


        $response = tbl_fondos::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $json_model_anterior = $model_anterior->toJson();

        $json_model_actual = $model_original->toJson();
        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->fondo_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('fondos.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_fondos::get_by_id($id);

        if(!$model)
            abort(404);


        return view('fondos.details')
            ->with(compact('model'));
    }
    
    #region Ajax
    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('fondo_id');

            $model = tbl_fondos::get_by_id($id);
            $model->activo = !$model->activo;

            $response = tbl_fondos::edit($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->fondo_id, movimiento_bitacora::Desactivado, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved' => true,
                'Message'   => $model->activo ? 'Se reactivo correctamente la información.' : 'Se desactivo correctamente la información.'
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => 'Ocurrio un error al intentar guardar la información.'
        ));
    }
    #endregion
}
