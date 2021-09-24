<?php

namespace App\Http\Controllers;


use App\Enums\catalago_sistema;
use App\Enums\movimiento_bitacora;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\DivisasRequest;
use App\tbl_ciudades;
use App\tbl_divisas;
use App\tbl_sucursales;

class DivisasController extends Controller
{
    private $catalago_sistema = catalago_sistema::Sucursales;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_divisas::get_list();

        return view('divisas.index')
            ->with(compact("model"));
    }

    public function edit($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_divisas::get_by_id($id);
        if(!$model)
            abort(404);

        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');
        $ciudades = tbl_ciudades::get_list()->pluck('ciudad', 'ciudad_id');
        return view('divisas.edit')
            ->with(compact('model'))
            ->with(compact('sucursales'))
            ->with(compact('ciudades'));
    }

    public function edit_post(DivisasRequest $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $id = request('divisa_id');

        $model = tbl_divisas::get_by_id($id);
        if(!$model)
            abort(404);

        $sucursal_id = request('sucursal_id');
        $ciudad_id = request('ciudad_id');
        $divisa_compra = request('divisa_compra');
        $divisa_venta = request('divisa_venta');
        $iva_divisa = request('iva_divisa');


        if(!empty($sucursal_id)){
            $sucursal = tbl_sucursales::get_by_id($sucursal_id);
            $sucursal_anterior = tbl_sucursales::get_by_id($sucursal_id);

            switch ($id)
            {
                //Dolar
                case 1:
                    $sucursal->dolar_compra = $divisa_compra;
                    $sucursal->dolar_venta = $divisa_venta;
                    break;
                //Dolar moneda
                case 2:
                    $sucursal->dolar_moneda_compra = $divisa_compra;
                    $sucursal->dolar_moneda_venta = $divisa_venta;
                    break;
                //Euro
                case 3:
                    $sucursal->euro_compra = $divisa_compra;
                    $sucursal->euro_venta = $divisa_venta;
                    break;
            }
            $sucursal->iva_divisa = $iva_divisa;

            $response = tbl_sucursales::edit($sucursal);

            if(!$response['saved'])
            {
                return redirect()->back()->withInput()
                    ->with('error',$response['error']);
            }

            $json_model_anterior = $sucursal_anterior->toJson();

            $json_model_actual = $sucursal->toJson();
            if($json_model_actual != $json_model_anterior)
                HelperCrediuno::save_bitacora($sucursal->sucursal_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);
        }else{
            $sucursales = tbl_sucursales::get_list_by_ciudad_id($ciudad_id);
            $sucursales_anterior = tbl_sucursales::get_list_by_ciudad_id($ciudad_id);

            foreach ($sucursales as $sucursal)
            {
                switch ($id)
                {
                    //Dolar
                    case 1:
                        $sucursal->dolar_compra = $divisa_compra;
                        $sucursal->dolar_venta = $divisa_venta;
                        break;
                    //Dolar moneda
                    case 2:
                        $sucursal->dolar_moneda_compra = $divisa_compra;
                        $sucursal->dolar_moneda_venta = $divisa_venta;
                        break;
                    //Euro
                    case 3:
                        $sucursal->euro_compra = $divisa_compra;
                        $sucursal->euro_venta = $divisa_venta;
                        break;
                }
                $sucursal->iva_divisa = $iva_divisa;

                $response = tbl_sucursales::edit($sucursal);

                if(!$response['saved'])
                {
                    return redirect()->back()->withInput()
                        ->with('error',$response['error']);
                }
                $sucursal_anterior = $sucursales_anterior->where('sucursal_id', $sucursal->sucursal_id)->first();
                $json_model_anterior = $sucursal_anterior->toJson();

                $json_model_actual = $sucursal->toJson();
                if($json_model_actual != $json_model_anterior)
                    HelperCrediuno::save_bitacora($sucursal->sucursal_id, movimiento_bitacora::Edicion, $this->catalago_sistema, $json_model_anterior, $json_model_actual);
            }
        }

        return redirect()->route('divisas.index');
    }
}
