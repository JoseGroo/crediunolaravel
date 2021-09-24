<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\tbl_cortes;
use Auth;
use Illuminate\Http\Request;
use Lang;

class CortesController extends Controller
{
    private $catalago_sistema = catalago_sistema::Clientes;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create_post()
    {

        if(Auth::user()->tiene_corte_abierto){
            return redirect()->back()->withInput(request()->all())
                ->with('error', Lang::get('dictionary.message_already_open_corte'));
        }

        $datetime_now = HelperCrediuno::get_datetime();
        $corte = new tbl_cortes();
        $corte->usuario_id = Auth::user()->id;
        $corte->fondos = 0;
        $corte->cerrado = false;
        $corte->activo = true;
        $corte->creado_por = auth()->user()->id;
        $corte->fecha_creacion = $datetime_now;

        $response = tbl_cortes::create($corte);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        HelperCrediuno::save_bitacora($corte->corte_id, movimiento_bitacora::CreoNuevoRegistro, $this->catalago_sistema, null, null);

        \request()->session()->flash('success_message', 'Se abrio correctamente su corte');
        return redirect()->route('home');
    }
}
