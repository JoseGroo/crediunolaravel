<?php

namespace App\Http\Controllers;

use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Lang;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        /*$this->middleware(function ($request, $next) {
            if(Auth::check()){
                return redirect()->route('home')
                    ->with('status','Inicio sesion correctamente.');
            }
        });*/
    }

    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'usuario' => 'required',
            'contrasena' => 'required',
        ], [
            'required' => Lang::get('dictionary.message_required_field')
        ]);


        if(auth()->attempt(array('usuario' => $input['usuario'], 'password' => $input['contrasena'])))
        {
            if(!auth()->user()->activo)
            {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error',Lang::get('dictionary.message_user_not_exists'));
            }

            HelperCrediuno::save_bitacora(null, movimiento_bitacora::InicioSesion, null, null, null);

            return redirect()->intended('');

            //return redirect()->route('home');
        }else{
            return redirect()->route('login')
                ->with('error', Lang::get('dictionary.message_wrong_user_password'));
        }
    }

    public function logoff() {

        if(\auth()->user())
            HelperCrediuno::save_bitacora(null, movimiento_bitacora::CerroSesion, null, null, null);

        Auth::logout();

        return redirect()->route('login');
    }
}
