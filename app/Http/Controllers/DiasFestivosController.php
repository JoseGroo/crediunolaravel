<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\DiasFestivosRequest;
use App\tbl_dias_festivos;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DiasFestivosController extends Controller
{
    private $catalago_sistema = catalago_sistema::DiasFestivos;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $fecha_inicio = request('fecha_inicio');
        $fecha_fin = request('fecha_fin');
        $razon = request('sRazon');
        $mostrar_dias_pasados = request('mostrar_dias_pasados');

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_dias_festivos::get_pagination($fecha_inicio, $fecha_fin, $razon, $mostrar_dias_pasados, $perPage);
        if($request->ajax()){
            return view('dias_festivos._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        return view('dias_festivos.index')
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);
        
        return view('dias_festivos.create');
    }

    public function create_post(DiasFestivosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_dias_festivos::check_if_exists(request('fecha'), 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', 'Ya existe un registro con la misma fecha. Intente con otra');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_dias_festivos($data_model);
        $model->fecha = Carbon::createFromFormat('d/m/Y', $model->fecha);

        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_dias_festivos::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->dia_festivo_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('dias_festivos.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_dias_festivos::get_by_id($id);
        if(!$model || !$model->activo)
            abort(404);

        $model->fecha = Carbon::parse($model->fecha)->format('d/m/Y');
        return view('dias_festivos.edit')
            ->with(compact('model'));
    }

    public function edit_post(DiasFestivosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('dia_festivo_id');
        $model_original = tbl_dias_festivos::get_by_id($id);
        $model_anterior = tbl_dias_festivos::get_by_id($id);

        if(!$model_original || !$model_original->activo)
            abort(404);

        if(tbl_dias_festivos::check_if_exists(request('fecha'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con la misma fecha. Intente con otra');
        }

        $model_original->fecha = Carbon::createFromFormat('d/m/Y', request('fecha'));
        $model_original->razon = request('razon');


        $response = tbl_dias_festivos::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $model_anterior->fecha = Carbon::parse($model_anterior->fecha)->format('d-m-Y');
        $json_model_anterior = $model_anterior->toJson();

        $model_original->fecha = Carbon::parse($model_original->fecha)->format('d-m-Y');
        $json_model_actual = $model_original->toJson();
        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->dia_festivo_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('dias_festivos.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_dias_festivos::get_by_id($id);

        if(!$model)
            abort(404);

        $model->fecha = Carbon::parse($model->fecha)->format('d-m-Y');
        return view('dias_festivos.details')
            ->with(compact('model'));
    }

    #region Ajax
    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('dia_festivo_id');

            $model = tbl_dias_festivos::get_by_id($id);
            $model->activo = false;

            $response = tbl_dias_festivos::edit($model);

            if($response['saved'] == false)
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->dia_festivo_id, movimiento_bitacora::Elimino, $this->catalago_sistema, null, null);
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
