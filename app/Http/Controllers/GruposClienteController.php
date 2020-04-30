<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\GruposRequest;
use App\tbl_grupos;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class GruposClienteController extends Controller
{
    private $catalago_sistema = catalago_sistema::Grupos;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $grupo = request('sGrupo');

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_grupos::get_pagination($grupo, $perPage);
        if($request->ajax()){
            return view('grupos_cliente._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        return view('grupos_cliente.index')
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        return view('grupos_cliente.create');
    }

    public function create_post(GruposRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_grupos::check_if_exists(request('grupo'), 0)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otro');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_grupo = request()->except(['_token', '_method']);

        $model = new tbl_grupos($data_grupo);
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_grupos::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->grupo_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('grupos-cliente.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_grupos::get_by_id($id);
        if(!$model)
            abort(404);

        return view('grupos_cliente.edit')
            ->with(compact('model'));
    }

    public function edit_post(GruposRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('grupo_id');
        $model_original = tbl_grupos::get_by_id($id);
        $model_anterior = tbl_grupos::get_by_id($id);

        if(!$model_original)
            abort(404);

        if(tbl_grupos::check_if_exists(request('grupo'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otro');
        }

        $model_original->grupo = request('grupo');


        $response = tbl_grupos::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $json_model_anterior = $model_anterior->toJson();

        $json_model_actual = $model_original->toJson();
        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->grupo_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('grupos-cliente.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_grupos::get_by_id($id);

        if(!$model)
            abort(404);


        return view('grupos_cliente.details')
            ->with(compact('model'));
    }

    #region Ajax
    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('grupo_id');

            $model = tbl_grupos::get_by_id($id);
            $model->activo = false;

            $response = tbl_grupos::edit($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->grupo_id, movimiento_bitacora::Elimino, $this->catalago_sistema, null, null);
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
