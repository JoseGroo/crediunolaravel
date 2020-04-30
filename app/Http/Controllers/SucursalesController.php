<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\SucursalesRequest;
use App\tbl_bitacora;
use App\tbl_ciudades;
use App\tbl_estados;
use App\tbl_sucursales;
use App\tbl_usuarios;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use JsValidator;
use PHPUnit\TextUI\Help;

class SucursalesController extends Controller
{
    private $catalago_sistema = catalago_sistema::Sucursales;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {

        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $sucursal = request('sSucursal');
        $estado_id = request('estado_id');
        $ciudad_id = request('ciudad_id');

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_sucursales::get_pagination($sucursal, $estado_id, $ciudad_id, $perPage);
        if($request->ajax()){
            return view('sucursales._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
        $ciudades = tbl_ciudades::get_list()->pluck('ciudad', 'ciudad_id');

        return view('sucursales.index')
            ->with(compact("model"))
            ->with(compact("estados"))
            ->with(compact("ciudades"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);


        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
        $ciudades = tbl_ciudades::get_list_by_estado_id(old('estado_id'))->pluck('ciudad', 'ciudad_id');


        return view('sucursales.create')
            ->with(compact('estados'))
            ->with(compact('ciudades'));
    }

    public function create_post(SucursalesRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_sucursales::check_if_exists(request('sucursal'), 0)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otro');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_sucursal = request()->except(['_token', '_method']);

        $model = new tbl_sucursales($data_sucursal);
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_sucursales::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->sucursal_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('sucursales.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_sucursales::get_by_id($id);
        $model->ciudad = $model->ciudad->ciudad_id;
        $model->estado = "Sonora";
        //dd($model->toJson());
        if(!$model)
            abort(404);

        $estados = tbl_estados::get_list()->pluck('estado', 'estado_id');
        $ciudades = tbl_ciudades::get_list_by_estado_id(old('estado_id') ?? $model->estado_id)->pluck('ciudad', 'ciudad_id');

        return view('sucursales.edit')
            ->with(compact('model'))
            ->with(compact('estados'))
            ->with(compact('ciudades'));
    }

    public function edit_post(SucursalesRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('sucursal_id');
        $model_original = tbl_sucursales::get_by_id($id);
        $model_anterior = tbl_sucursales::get_by_id($id);

        if(!$model_original)
            abort(404);

        if(tbl_sucursales::check_if_exists(request('sucursal'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre. Intente con otro');
        }

        $model_original->estado_id = request('estado_id');
        $model_original->ciudad_id = request('ciudad_id');
        $model_original->sucursal = request('sucursal');
        $model_original->numero_contrato = request('numero_contrato');
        $model_original->direccion = request('direccion');
        $model_original->telefono = request('telefono');
        $model_original->beneficiario = request('beneficiario');
        $model_original->dolar_compra = request('dolar_compra');
        $model_original->dolar_venta = request('dolar_venta');
        $model_original->euro_compra = request('euro_compra');
        $model_original->euro_venta = request('euro_venta');
        $model_original->dolar_moneda_compra = request('dolar_moneda_compra');
        $model_original->dolar_moneda_venta = request('dolar_moneda_venta');


        $response = tbl_sucursales::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $model_anterior->estado_name = $model_anterior->estado->estado;
        $model_anterior->ciudad_name = $model_anterior->ciudad->ciudad;
        $json_model_anterior = $model_anterior->toJson();

        $model_original->estado_name = $model_original->estado->estado;
        $model_original->ciudad_name = $model_original->ciudad->ciudad;
        $json_model_actual = $model_original->toJson();

        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->sucursal_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('sucursales.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_sucursales::get_by_id($id);

        if(!$model)
            abort(404);


        return view('sucursales.details')
            ->with(compact('model'));
    }

    #region Ajax
    public function get_ciudades_by_estado_id(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $estado_id = request('estado_id');

            $ciudades = tbl_ciudades::get_list_by_estado_id($estado_id);

            return Response::json($ciudades);
        }

        return Response::json(null);
    }

    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('sucursal_id');

            $model = tbl_sucursales::get_by_id($id);
            $model->activo = false;

            $response = tbl_sucursales::edit($model);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->sucursal_id, movimiento_bitacora::Elimino, $this->catalago_sistema, null, null);
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
