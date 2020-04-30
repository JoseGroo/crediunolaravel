<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\UserRequest;
use App\tbl_roles;
use App\tbl_sucursales;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\tbl_usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use PHPUnit\TextUI\Help;
use Symfony\Component\Console\Input\Input;

class UsersController extends Controller
{
    private $catalago_sistema = catalago_sistema::Usuarios;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $nombre = request('sNombre');
        $perPage = request('iPerPage') ?? 12;

        $users = tbl_usuarios::get_pagination($nombre, $perPage);
        if($request->ajax()){
            return view('users._index')
                ->with(compact("users"))
                ->with(compact('perPage'));
        }

        return view('users.index')
            ->with(compact("users"))
            ->with(compact('perPage'));
    }

    public function create()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $roles = tbl_roles::get_list()->pluck('rol', 'rol_id');
        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');

        return view('users.create')
            ->with(compact('roles'))
            ->with(compact('sucursales'));
    }

    public function create_post(UserRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if(tbl_usuarios::check_if_exists(request('usuario'), 0)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre de usuario. Intente con otro');
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $foto = request('foto');
        $ruta_foto = $foto ? HelperCrediuno::save_file($foto, 'public/user_profile') : "";

        $data_usuario = request()->except(['_token', '_method']);

        $usuario = new tbl_usuarios($data_usuario);
        $usuario->foto = $ruta_foto;
        $usuario->password = Hash::make($usuario->password);
        $usuario->activo = true;
        $usuario->creado_por = Auth::user()->id;
        $usuario->fecha_creacion = $datetime_now;

        $response = tbl_usuarios::create($usuario);

        if(!$response['saved'])
        {
            Storage::delete($ruta_foto);
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }
        HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);


        return redirect()->route('users.index');
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $usuario = tbl_usuarios::get_by_id($id);

        if(!$usuario || !$usuario->activo)
            abort(404);

        $roles = tbl_roles::get_list()->pluck('rol', 'rol_id');
        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');

        return view('users.edit')
            ->with(compact('roles'))
            ->with(compact('sucursales'))
            ->with(compact('usuario'));
    }

    public function edit_post(UserRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('id');
        $usuario_anterior = tbl_usuarios::get_by_id($id);
        $usuario_original = tbl_usuarios::get_by_id($id);

        if(!$usuario_original || !$usuario_original->activo)
            abort(404);

        if(tbl_usuarios::check_if_exists(request('usuario'), $id)){
            return redirect()->back()->withInput()
                ->with('error', 'Ya existe un registro con el mismo nombre de usuario. Intente con otro');
        }

        $foto = request('foto');
        $ruta_foto = $foto ? HelperCrediuno::save_file($foto, 'public/user_profile') : "";

        $usuario_original->nombre = request('nombre');
        $usuario_original->apellido_paterno = request('apellido_paterno');
        $usuario_original->apellido_materno = request('apellido_materno');
        $usuario_original->rol_id = request('rol_id');
        $usuario_original->usuario = request('usuario');
        $usuario_original->sucursal_id = request('sucursal_id');
        $usuario_original->direccion = request('direccion');
        $usuario_original->telefono = request('telefono');
        $usuario_original->sucursal_id = request('sucursal_id');
        $usuario_original->foto = $foto ? $ruta_foto : $usuario_original->foto;


        $response = tbl_usuarios::edit($usuario_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }
        $usuario_anterior->rol_name = $usuario_anterior->rol->rol;
        $usuario_anterior->sucursal_name = $usuario_anterior->sucursal->sucursal;
        $json_model_anterior = $usuario_anterior->toJson();

        $usuario_original->rol_name = $usuario_original->rol->rol;
        $usuario_original->sucursal_name = $usuario_original->sucursal->sucursal;
        $json_model_actual = $usuario_original->toJson();
        if($json_model_anterior != $json_model_actual)
            HelperCrediuno::save_bitacora($usuario_original->id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('users.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $usuario = tbl_usuarios::get_by_id($id);

        if(!$usuario)
            abort(404);


        return view('users.details')
            ->with(compact('usuario'));
    }

    #region Ajax
    public function delete(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('id');

            $usuario = tbl_usuarios::get_by_id($id);
            $usuario->activo = !$usuario->activo;

            $response = tbl_usuarios::edit($usuario);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::Desactivado, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved' => true,
                'Message'   => $usuario->activo ? 'Se reactivo correctamente la información.' : 'Se desactivo correctamente la información.'
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => 'Ocurrio un error al intentar guardar la información.'
        ));
    }

    public function cambiar_contrasena(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        if($request->ajax()){
            $id = request('id');

            $usuario = tbl_usuarios::get_by_id($id);

            if(!$usuario || !$usuario->activo)
            {
                $message = !$usuario ? 'No se encontro usuario.' : 'El usuario se encuentra desactivado.';
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$message
                ));
            }

            $contrasena = request('password');
            $usuario->password = Hash::make($contrasena);

            $response = tbl_usuarios::edit($usuario);

            if(!$response['saved'])
            {
                return Response::json(array(
                    'Saved' => false,
                    'Message'   => 'Ocurrio un error al intentar guardar la información: '.$response['error']
                ));
            }

            HelperCrediuno::save_bitacora($usuario->id, movimiento_bitacora::CambioContrasena, $this->catalago_sistema, null, null);
            return Response::json(array(
                'Saved' => true,
                'Message'   => 'Se guardo correctamente la información.'
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => 'Ocurrio un error al intentar guardar la información.'
        ));
    }
    #endregion

}