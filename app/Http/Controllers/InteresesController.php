<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\InteresesRequest;
use App\tbl_intereses;
use Illuminate\Http\Request;

class InteresesController extends Controller
{
    private $catalago_sistema = catalago_sistema::Intereses;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_intereses::get_list();
        if($request->ajax()){
            return view('intereses._index')
                ->with(compact("model"));
        }

        return view('intereses.index')
            ->with(compact("model"));
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_intereses::get_by_id($id);

        if(!$model)
            abort(404);

        return view('intereses.edit')
            ->with(compact('model'));
    }

    public function edit_post(InteresesRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('interes_id');
        $model_original = tbl_intereses::get_by_id($id);
        $model_anterior = tbl_intereses::get_by_id($id);

        if(!$model_original)
            abort(404);

        $model_original->nombre = request('nombre');
        $model_original->interes_mensual = request('interes_mensual');
        $model_original->interes_diario = request('interes_diario');
        $model_original->otros_intereses = request('otros_intereses');
        $model_original->iva = request('iva');
        $model_original->comision_apertura = request('comision_apertura');
        $model_original->manejo_cuenta = request('manejo_cuenta');
        $model_original->gastos_papeleria = request('gastos_papeleria');
        $model_original->gastos_cobranza = request('gastos_cobranza');
        $model_original->seguro_desempleo = request('seguro_desempleo');
        $model_original->iva_otros_conceptos = request('iva_otros_conceptos');
        $model_original->imprimir_logo = request('imprimir_logo') == 1 ? true : false;
        $model_original->imprimir_datos_empresa = request('imprimir_datos_empresa') == 1 ? true : false;

        $response = tbl_intereses::edit($model_original);

        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $json_model_anterior = $model_anterior->toJson();

        $json_model_actual = $model_original->toJson();

        if($json_model_actual != $json_model_anterior)
            HelperCrediuno::save_bitacora($model_original->interes_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);

        return redirect()->route('intereses.index');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_intereses::get_by_id($id);

        if(!$model)
            abort(404);


        return view('intereses.details')
            ->with(compact('model'));
    }
}
