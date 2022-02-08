<?php

namespace App\Http\Controllers;


use App\Enums\catalago_sistema;
use App\Enums\estatus_movimientos_corte;
use App\Enums\formas_pago;
use App\Enums\movimiento_bitacora;
use App\Enums\tipo_compra_venta_divisa;
use App\Enums\tipos_movimientos_corte;
use App\Helpers\HelperCrediuno;
use App\Http\Requests\CompraVentaDivisaRequest;
use App\Http\Requests\DivisasRequest;
use App\tbl_ciudades;
use App\tbl_compra_venta_divisas;
use App\tbl_cortes;
use App\tbl_divisas;
use App\tbl_movimientos_corte;
use App\tbl_sucursales;
use Illuminate\Http\Request;

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

    public function compra_venta($id = 0)
    {
        if(!auth()->user()->tiene_corte_abierto){
            \request()->session()->flash('error_message', 'No tiene corte abierto para realizar esta operación.');
            return redirect()->route('home');
        }

        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol, HelperCrediuno::$admin_rol, HelperCrediuno::$ventanilla]);

        $sucursal = auth()->user()->sucursal;
        $divisas = tbl_divisas::get_list()->pluck('divisa', 'divisa_id');
        $tipo_compra_venta_divisa = tipo_compra_venta_divisa::toSelectArray();

        $compra_venta_divisas = session('compra_venta_divisas');

        return view('divisas.comprar_venta')
            ->with(compact('divisas'))
            ->with(compact('tipo_compra_venta_divisa'))
            ->with(compact('compra_venta_divisas'))
            ->with(compact('sucursal'));
    }

    public function compra_venta_post(CompraVentaDivisaRequest $request)
    {
        if(!auth()->user()->tiene_corte_abierto){
            \request()->session()->flash('error_message', 'No tiene corte abierto para realizar esta operación.');
            return redirect()->route('home');
        }

        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol, HelperCrediuno::$admin_rol, HelperCrediuno::$ventanilla]);

        $datetime_now = HelperCrediuno::get_datetime();
        $data_model = request()->except(['_token', '_method']);

        $sucursal = auth()->user()->sucursal;
        $model = new tbl_compra_venta_divisas($data_model);

        $compra_divisa  = 0;
        $venta_divisa  = 0;

        switch ($model->divisa_id)
        {
            case '1':
                $compra_divisa = $sucursal->dolar_compra;
                $venta_divisa = $sucursal->dolar_venta;
                break;
            case '2':
                $compra_divisa = $sucursal->dolar_moneda_compra;
                $venta_divisa = $sucursal->dolar_moneda_venta;
                break;
            case '3':
                $compra_divisa = $sucursal->euro_compra;
                $venta_divisa = $sucursal->euro_venta;
                break;
        }

        //COMPRA
        $total = 0;
        $paga_con = $request->paga_con;
        $importe_iva = 0;
        $cambio = 0;
        if($model->tipo == tipo_compra_venta_divisa::Compra)
        {
             $total = $model->cantidad * $compra_divisa;
        }
        //VENTA
        else{
            $total = $model->cantidad * $venta_divisa;

            if($sucursal->iva_divisa > 0)
            {
                $importe_iva = ($total * $sucursal->iva_divisa) / 100;
                $total += $importe_iva;
            }


            $paga_con = !empty($paga_con) ? $paga_con : $total;
            $cambio = $paga_con - $total;
        }
        $model->importe = $total;
        $model->importe_iva = $importe_iva;
        $model->activo = true;
        $model->creado_por = auth()->user()->id;
        $model->fecha_creacion = $datetime_now;

        $response = tbl_compra_venta_divisas::create($model);

        $model->pago_con = $paga_con;
        $model->cambio = $cambio;
        if(!$response['saved'])
        {
            return redirect()->back()->withInput()
                ->with('error',$response['error']);
        }

        $operacion = new tbl_movimientos_corte();
        $operacion->cliente_id = 0;
        $operacion->tipo = $model->tipo == tipo_compra_venta_divisa::Compra ? tipos_movimientos_corte::CompraDivisa : tipos_movimientos_corte::VentaDivisa;
        $operacion->importe = $model->importe;
        $operacion->corte_id = auth()->user()->corte_id;;
        $operacion->metodo_pago = formas_pago::Efectivo;
        $operacion->external_id = $model->compra_venta_divisa_id;
        $operacion->estatus = estatus_movimientos_corte::Activo;
        $operacion->cantidad_divisa = $model->cantidad;
        $operacion->divisa_id = $model->divisa_id;
        $operacion->activo = true;
        $operacion->creado_por = auth()->user()->id;
        $operacion->fecha_creacion = $datetime_now;

        $response = tbl_movimientos_corte::create($operacion);

        HelperCrediuno::save_bitacora($model->compra_venta_divisa_id, movimiento_bitacora::CreoNuevoRegistro, catalago_sistema::Divisas, null, null);

        \request()->session()->flash('compra_venta_divisas', $model);
        return redirect()->route('divisas.compra_venta');
    }

    public function download_pdf(Request $request){
        $compra_venta_divisa = tbl_compra_venta_divisas::get_by_id($request->compra_venta_divisa_id);
        $compra_venta_divisa->pago_con = $request->pago_con;
        $compra_venta_divisa->cambio = $request->cambio;
        $data = [
            'compra_venta_divisa' => $compra_venta_divisa
        ];

        return HelperCrediuno::generate_pdf($data, 'divisas.pdf_compra_venta', 'ticket-divisa');
    }
}
