<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\ContactosRequest;
use App\tbl_contactos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ContactosController extends Controller
{
    private $catalago_sistema = catalago_sistema::Contactos;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $nombre = request('sNombre');
        $telefono = request('sTelefono');

        $perPage = request('iPerPage') ?? 10;

        $model = tbl_contactos::get_pagination($nombre, $telefono, $perPage);
        if($request->ajax()){
            return view('contactos._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        return view('contactos.index')
            ->with(compact("model"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        return view('contactos.create');
    }

    public function create_post(ContactosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_contactos::check_if_exists(request('nombre'), request('telefono'), 0)){
            return redirect()->back()->withInput(request()->all())
                ->with('error', 'Ya existe un registro con el mismo nombre o teléfono. Intente con otra');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $model = new tbl_contactos($data_model);

        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_contactos::create($model);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($model->contacto_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);
        return redirect()->route('contactos.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_contactos::get_by_id($id);
        if(!$model || !$model->activo)
            abort(404);

        return view('contactos.edit')
            ->with(compact('model'));
    }

    public function edit_post(ContactosRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('contacto_id');
        $model_original = tbl_contactos::get_by_id($id);
        $model_anterior = tbl_contactos::get_by_id($id);

        if(!$model_original || !$model_original->activo)
            abort(404);

        if(tbl_contactos::check_if_exists(request('nombre'), request('telefono'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre o teléfono. Intente con otra');
        }

        $model_original->nombre = request('nombre');
        $model_original->direccion = request('direccion');
        $model_original->telefono = request('telefono');
        $model_original->correo_electronico = request('correo_electronico');

        $response = tbl_contactos::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $json_model_anterior = $model_anterior->toJson();

        $json_model_actual = $model_original->toJson();
        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->contacto_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('contactos.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_contactos::get_by_id($id);

        if(!$model)
            abort(404);

        return view('contactos.details')
            ->with(compact('model'));
    }

    #region Ajax
    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('contacto_id');

            $model = tbl_contactos::get_by_id($id);
            $model->activo = false;

            $response = tbl_contactos::edit($model);

            if($response['saved'] == false)
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($model->contacto_id, movimiento_bitacora::Elimino, $this->catalago_sistema, null, null);
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
