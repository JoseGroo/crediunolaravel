<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\MediosPublicitariosRequest;
use App\tbl_medios_publicitarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MediosPublicitariosController extends Controller
{
    private $catalago_sistema = catalago_sistema::MediosPublicitarios;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $medio_publicitario = request('sMedioPublicitario');

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_medios_publicitarios::get_pagination($medio_publicitario, $perPage);
        if($request->ajax()){
            return view('medios_publicitarios._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        return view('medios_publicitarios.index')
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        return view('medios_publicitarios.create');
    }

    public function create_post(MediosPublicitariosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_medios_publicitarios::check_if_exists(request('medio_publicitario'), 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otra');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_medios_publicitarios($data_model);

        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_medios_publicitarios::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->medio_publicitario_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('medios-publicitarios.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_medios_publicitarios::get_by_id($id);
        if(!$model || !$model->activo)
            abort(404);

        return view('medios_publicitarios.edit')
            ->with(compact('model'));
    }

    public function edit_post(MediosPublicitariosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('medio_publicitario_id');
        $model_original = tbl_medios_publicitarios::get_by_id($id);
        $model_anterior = tbl_medios_publicitarios::get_by_id($id);

        if(!$model_original || !$model_original->activo)
            abort(404);

        if(tbl_medios_publicitarios::check_if_exists(request('medio_publicitario'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre o teléfono. Intente con otra');
        }

        $model_original->medio_publicitario = request('medio_publicitario');

        $response = tbl_medios_publicitarios::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $json_model_anterior = $model_anterior->toJson();

        $json_model_actual = $model_original->toJson();
        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->medio_publicitario_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('medios-publicitarios.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_medios_publicitarios::get_by_id($id);

        if(!$model)
            abort(404);

        return view('medios_publicitarios.details')
            ->with(compact('model'));
    }

    #region Ajax
    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('medio_publicitario_id');

            $model = tbl_medios_publicitarios::get_by_id($id);
            $model->activo = false;

            $response = tbl_medios_publicitarios::edit($model);

            if($response['saved'] == false)
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->medio_publicitario_id, movimiento_bitacora::Elimino, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved' => true,
                'Message'   => 'Se elimino correctamente la información.'
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => 'Ocurrio un error al intentar guardar la información.'
        ));
    }
    #endregion
}
