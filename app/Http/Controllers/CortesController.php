<?php

namespace App\Http\Controllers;

use App\Enums\catalago_sistema;
use App\Enums\estatus_adeudos;
use App\Enums\estatus_descuentos;
use App\Enums\estatus_movimientos_corte;
use App\Enums\estatus_prestamo;
use App\Enums\formas_pago;
use App\Enums\movimiento_bitacora;
use App\Enums\tipo_transferencia_fondo;
use App\Enums\tipos_movimientos_corte;
use App\Helpers\HelperCrediuno;
use App\tbl_adeudos;
use App\tbl_cargos;
use App\tbl_cobro_otros_conceptos;
use App\tbl_compra_venta_divisas;
use App\tbl_cortes;
use App\tbl_descuentos;
use App\tbl_divisas;
use App\tbl_fondos;
use App\tbl_movimientos_corte;
use App\tbl_pagos;
use App\tbl_prestamos;
use App\tbl_sucursales;
use App\tbl_transferencias_entre_cajas;
use App\tbl_transferencias_fondos;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Lang;

class CortesController extends Controller
{
    private $catalago_sistema = catalago_sistema::Cortes;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $perPage = $request->iPerPage ?? 10;

        $model = tbl_cortes::get_pagination($request->fecha_inicio, $request->fecha_fin, $request->sucursal_id, $request->estatus, $perPage);
        if($request->ajax()){
            return view('cortes._index')
                ->with(compact("model"))
                ->with(compact('perPage'));
        }

        $sucursales = tbl_sucursales::get_list()->pluck('sucursal', 'sucursal_id');

        return view('cortes.index')
            ->with(compact("model"))
            ->with(compact("sucursales"))
            ->with(compact('perPage'));
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

        \request()->session()->flash('corte', $corte);
        return redirect()->route('home');
    }

    public function download_pdf(Request $request){
        $corte = tbl_cortes::get_by_id($request->corte_id);

        $data = [
            'user' => Auth::user(),
            'corte' => $corte
        ];

        return HelperCrediuno::generate_pdf($data, 'cortes.pdf_corte_abierto', 'ticket-corte-abierto');
    }

    public function details($id = 0)
    {
        auth()->user()->authorizeRoles([HelperCrediuno::$admin_gral_rol]);

        $model = tbl_cortes::get_by_id($id);

        if(!$model)
            abort(404);

        return view('cortes.details')
            ->with(compact('model'));
    }

    public function hacer_transferencia_post()
    {
        $datetime_now = HelperCrediuno::get_datetime();

        $data_model = request()->except(['_token', '_method']);

        $model_create = new tbl_transferencias_fondos($data_model);

        $model_create->estatus = estatus_movimientos_corte::Activo;
        $model_create->activo = true;
        $model_create->creado_por = auth()->user()->id;
        $model_create->fecha_creacion = $datetime_now;

        $response = tbl_transferencias_fondos::create($model_create);

        if(!$response['saved'])
        {
            return Response::json(array(
                'Saved'     => false,
                'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
            ));
        }

        $fondo = tbl_fondos::get_by_id($model_create->fondo_id);
        switch ($model_create->divisa_id){
            case 0:
                if($model_create->tipo == tipo_transferencia_fondo::Transferencia)
                    $fondo->importe_pesos -= $model_create->importe;
                else
                    $fondo->importe_pesos += $model_create->importe;
                break;
            case 1:
                if($model_create->tipo == tipo_transferencia_fondo::Transferencia)
                    $fondo->importe_dolares -= $model_create->importe;
                else
                    $fondo->importe_dolares += $model_create->importe;
                break;
            case 2:
                if($model_create->tipo == tipo_transferencia_fondo::Transferencia)
                    $fondo->importe_dolares_moneda -= $model_create->importe;
                else
                    $fondo->importe_dolares_moneda += $model_create->importe;
                break;
            case 3:
                if($model_create->tipo == tipo_transferencia_fondo::Transferencia)
                    $fondo->importe_euros -= $model_create->importe;
                else
                    $fondo->importe_euros += $model_create->importe;
                break;
        }
        $response = tbl_fondos::edit($fondo);

        HelperCrediuno::save_bitacora($model_create->transferencia_fondo_id, movimiento_bitacora::HizoTransferenciaRetiro, $this->catalago_sistema, null, null);


        $corte = tbl_cortes::get_by_id($model_create->corte_id);
        $model = tbl_transferencias_fondos::get_list_by_corte_id($model_create->corte_id);
        $fondos = tbl_fondos::get_list()->pluck('fondo', 'fondo_id');
        $divisas = tbl_divisas::get_list(true)->pluck('divisa', 'divisa_id');
        $tipos_transferencias = tipo_transferencia_fondo::toSelectArray();

        $html = view('cortes._traspasos_retiros')
            ->with(compact('model'))
            ->with(compact('fondos'))
            ->with(compact('divisas'))
            ->with(compact('tipos_transferencias'))
            ->with(compact('corte'))
            ->render();
        return Response::json(array(
            'Saved'     => true,
            'Message'   => Lang::get('dictionary.message_save_correctly'),
            'Html'      => $html
        ));
    }

    public function hacer_transferencia_caja_post()
    {
        $datetime_now = HelperCrediuno::get_datetime();

        $data_model = request()->except(['_token', '_method']);

        $model_create = new tbl_transferencias_entre_cajas($data_model);

        $model_create->estatus = estatus_movimientos_corte::Activo;
        $model_create->activo = true;
        $model_create->creado_por = auth()->user()->id;
        $model_create->fecha_creacion = $datetime_now;

        $response = tbl_transferencias_entre_cajas::create($model_create);

        if(!$response['saved'])
        {
            return Response::json(array(
                'Saved'     => false,
                'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
            ));
        }

        HelperCrediuno::save_bitacora($model_create->transferencia_entre_caja_id, movimiento_bitacora::HizoTransferenciaEntreCajas, $this->catalago_sistema, null, null);


        $corte = tbl_cortes::get_by_id($model_create->corte_origen_id);
        $cortes = tbl_cortes::get_list_abiertos($model_create->corte_origen_id)->pluck('nombre_completo_usuario', 'corte_id');
        $model = tbl_transferencias_entre_cajas::get_list_by_corte_id($model_create->corte_origen_id);
        $divisas = tbl_divisas::get_list(true)->pluck('divisa', 'divisa_id');

        $html = view('cortes._transferencias_entre_caja')
            ->with(compact('model'))
            ->with(compact('divisas'))
            ->with(compact('cortes'))
            ->with(compact('corte'))
            ->render();
        return Response::json(array(
            'Saved'     => true,
            'Message'   => Lang::get('dictionary.message_save_correctly'),
            'Html'      => $html
        ));
    }

    public function get_tab_details(Request $request)
    {
        switch ($request->tab)
        {
            case 'tab-resumen':
                $model = tbl_cortes::get_by_id($request->corte_id);
                return view('cortes._resumen')
                    ->with(compact('model'));
                break;
            case 'tab-movimientos':
                $corte = tbl_cortes::get_by_id($request->corte_id);
                $model = tbl_movimientos_corte::get_list_by_corte_id($request->corte_id);
                $divisas = tbl_divisas::get_list(true);
                $transferencias_rertiros = tbl_transferencias_fondos::get_list_by_corte_id($request->corte_id, true);
                return view('cortes._movimientos')
                    ->with(compact('transferencias_rertiros'))
                    ->with(compact('corte'))
                    ->with(compact('divisas'))
                    ->with(compact('model'));
                break;
            case 'tab-traspasos-retiros':
                $corte = tbl_cortes::get_by_id($request->corte_id);
                $model = tbl_transferencias_fondos::get_list_by_corte_id($request->corte_id);
                $fondos = tbl_fondos::get_list()->pluck('fondo', 'fondo_id');
                $divisas = tbl_divisas::get_list(true)->pluck('divisa', 'divisa_id');
                $tipos_transferencias = tipo_transferencia_fondo::toSelectArray();
                return view('cortes._traspasos_retiros')
                    ->with(compact('model'))
                    ->with(compact('fondos'))
                    ->with(compact('divisas'))
                    ->with(compact('tipos_transferencias'))
                    ->with(compact('corte'));
                break;
            case 'tab-traspasos-entre-cajas':
                $corte = tbl_cortes::get_by_id($request->corte_id);
                $cortes = tbl_cortes::get_list_abiertos($request->corte_id)->pluck('nombre_completo_usuario', 'corte_id');
                $model = tbl_transferencias_entre_cajas::get_list_by_corte_id($request->corte_id);
                $divisas = tbl_divisas::get_list(true)->pluck('divisa', 'divisa_id');
                return view('cortes._transferencias_entre_caja')
                    ->with(compact('model'))
                    ->with(compact('divisas'))
                    ->with(compact('cortes'))
                    ->with(compact('corte'));
                break;
            default:
                return "<h1>En construccion</h1>";
                break;
        }
    }

    public function cancelar_movimientos(Request $request)
    {
        try{
            if(request()->ajax())
            {
                $movimientos_cancelados = tbl_movimientos_corte::get_list_by_ids($request->ids);
                foreach ($movimientos_cancelados as $item){

                    switch ($item->tipo)
                    {
                        case tipos_movimientos_corte::EntregaPrestamo:

                            $prestamo = tbl_prestamos::get_by_id($item->external_id);

                            tbl_adeudos::delete_by_prestamo_id($item->external_id);
                            tbl_cargos::delete_by_prestamo_id($item->external_id);
                            $prestamo->activo = false;
                            tbl_prestamos::edit($prestamo);
                            HelperCrediuno::save_bitacora($item->external_id, movimiento_bitacora::CanceloPrestamoCorte, catalago_sistema::Prestamos, null, $prestamo->toJson());
                            break;
                        case tipos_movimientos_corte::PagosRecibos:
                        case tipos_movimientos_corte::PagosCargos:
                        $pago = tbl_pagos::get_by_id($item->external_id);
                        $prestamo_id = 0;
                        if($item->tipo == tipos_movimientos_corte::PagosRecibos){
                            $adeudo = tbl_adeudos::get_by_id($pago->external_id);
                            $adeudo->capital += $pago->capital;
                            $adeudo->interes += $pago->interes;
                            $adeudo->iva += $pago->iva;
                            $adeudo->importe_total = $adeudo->iva + $adeudo->interes + $adeudo->capital;
                            $adeudo->estatus = estatus_adeudos::Vigente;
                            tbl_adeudos::edit($adeudo);
                            $prestamo_id = $adeudo->prestamo_id;
                        }else{
                            $cargo = tbl_cargos::get_by_id($pago->external_id);
                            $cargo->capital += $pago->capital;
                            $cargo->interes += $pago->interes;
                            $cargo->iva += $pago->iva;
                            $cargo->importe_total = $cargo->iva + $cargo->interes + $cargo->capital;
                            $cargo->estatus = estatus_adeudos::Vigente;
                            tbl_cargos::edit_model($cargo);
                            $prestamo_id = $cargo->prestamo_id;
                        }

                        if($pago->metodo_pago == formas_pago::Descuento){
                            $descuento = tbl_descuentos::get_by_id($pago->descuento_id);
                            $descuento->importe += $pago->capital + $pago->interes + $pago->iva;
                            $descuento->importe_acreditado -= $pago->capital + $pago->interes + $pago->iva;
                            $descuento->estatus = estatus_descuentos::Vigente;
                            tbl_descuentos::edit($descuento);
                        }

                        $pago->activo = false;
                        tbl_pagos::edit($pago);

                        $prestamo = tbl_prestamos::get_by_id($prestamo_id);
                        $prestamo->estatus = estatus_prestamo::Vigente;
                        tbl_prestamos::edit($prestamo);

                        HelperCrediuno::save_bitacora($item->external_id, movimiento_bitacora::CanceloPagoAdeudoCorte, catalago_sistema::Adeudos, null, $pago->toJson());
                            break;
                        case tipos_movimientos_corte::VentaDivisa:
                        case tipos_movimientos_corte::CompraDivisa:
                        $compra_venta_divisa = tbl_compra_venta_divisas::get_by_id($item->external_id);
                        $compra_venta_divisa->activo = false;
                        tbl_compra_venta_divisas::edit($compra_venta_divisa);

                        HelperCrediuno::save_bitacora($item->external_id, movimiento_bitacora::CanceloCompraVentaDivisaCorte, catalago_sistema::Divisas, null, $compra_venta_divisa->toJson());
                            break;
                        case tipos_movimientos_corte::CobroOtroConcepto:
                            $cobro_otro_concepto = tbl_cobro_otros_conceptos::get_by_id($item->external_id);
                            $cobro_otro_concepto->activo = false;
                            tbl_cobro_otros_conceptos::edit($cobro_otro_concepto);

                            HelperCrediuno::save_bitacora($item->external_id, movimiento_bitacora::CanceloCobroOtroConceptoCorte, catalago_sistema::Divisas, null, $cobro_otro_concepto->toJson());
                            break;
                    }
                }

                tbl_movimientos_corte::cancel_by_ids($request->ids);

                $corte = tbl_cortes::get_by_id($request->corte_id);
                $model = tbl_movimientos_corte::get_list_by_corte_id($request->corte_id);
                $divisas = tbl_divisas::get_list(true);
                $transferencias_rertiros = tbl_transferencias_fondos::get_list_by_corte_id($request->corte_id, true);


                $html  = view('cortes._movimientos')
                    ->with(compact('transferencias_rertiros'))
                    ->with(compact('corte'))
                    ->with(compact('divisas'))
                    ->with(compact('model'))
                    ->render();
                return Response::json(array(
                    'Saved'     => true,
                    'Message'   => Lang::get('dictionary.message_save_correctly'),
                    'Html'      => $html
                ));
            }
        }catch (\Exception $e){
            //report($e);
            return Response::json(array(
                'Saved' => false,
                'Message'   => $e->getMessage()
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function cerrar(Request $request)
    {
        try{
            if(request()->ajax())
            {
                $datetime_now = HelperCrediuno::get_datetime();
                $model = tbl_cortes::get_by_id($request->corte_id);
                $model->fecha_cierre = $datetime_now;
                $model->cerrado = true;

                $response = tbl_cortes::edit($model);

                if(!$response['saved'])
                {
                    return Response::json(array(
                        'Saved'     => false,
                        'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                    ));
                }

                HelperCrediuno::save_bitacora($model->corte_id, movimiento_bitacora::CerroCorte, catalago_sistema::Cortes, null, $model->toJson());

                $html  = view('cortes._resumen')
                    ->with(compact('model'))
                    ->render();

                return Response::json(array(
                    'Saved'     => true,
                    'Message'   => Lang::get('dictionary.message_save_correctly'),
                    'Html'      => $html
                ));
            }
        }catch (\Exception $e){
            return Response::json(array(
                'Saved' => false,
                'Message'   => $e->getMessage()
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function cancelar_transferencia_fondo(Request $request)
    {
        try{
            if(request()->ajax())
            {
                $transferencia = tbl_transferencias_fondos::get_by_id($request->transferencia_fondo_id);
                $transferencia->estatus = estatus_movimientos_corte::Cancelado;

                $response = tbl_transferencias_fondos::edit($transferencia);

                if(!$response['saved'])
                {
                    return Response::json(array(
                        'Saved'     => false,
                        'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                    ));
                }

                $fondo = tbl_fondos::get_by_id($transferencia->fondo_id);
                switch ($fondo->divisa_id){
                    case 0:
                        if($transferencia->tipo != tipo_transferencia_fondo::Transferencia)
                            $fondo->importe_pesos -= $transferencia->importe;
                        else
                            $fondo->importe_pesos += $transferencia->importe;
                        break;
                    case 1:
                        if($transferencia->tipo != tipo_transferencia_fondo::Transferencia)
                            $fondo->importe_dolares -= $transferencia->importe;
                        else
                            $fondo->importe_dolares += $transferencia->importe;
                        break;
                    case 2:
                        if($transferencia->tipo != tipo_transferencia_fondo::Transferencia)
                            $fondo->importe_dolares_moneda -= $transferencia->importe;
                        else
                            $fondo->importe_dolares_moneda += $transferencia->importe;
                        break;
                    case 3:
                        if($transferencia->tipo != tipo_transferencia_fondo::Transferencia)
                            $fondo->importe_euros -= $transferencia->importe;
                        else
                            $fondo->importe_euros += $transferencia->importe;
                        break;
                }

                tbl_fondos::edit($fondo);

                HelperCrediuno::save_bitacora($transferencia->transferencia_fondo_id, movimiento_bitacora::CanceloTransferenciaRetiroCorte, catalago_sistema::Cortes, null, $transferencia->toJson());

                $corte = tbl_cortes::get_by_id($request->corte_id);
                $model = tbl_transferencias_fondos::get_list_by_corte_id($request->corte_id);
                $fondos = tbl_fondos::get_list()->pluck('fondo', 'fondo_id');
                $divisas = tbl_divisas::get_list(true)->pluck('divisa', 'divisa_id');
                $tipos_transferencias = tipo_transferencia_fondo::toSelectArray();

                $html  = view('cortes._traspasos_retiros')
                    ->with(compact('model'))
                    ->with(compact('fondos'))
                    ->with(compact('divisas'))
                    ->with(compact('tipos_transferencias'))
                    ->with(compact('corte'))
                    ->render();

                return Response::json(array(
                    'Saved'     => true,
                    'Message'   => Lang::get('dictionary.message_save_correctly'),
                    'Html'      => $html
                ));
            }
        }catch (\Exception $e){
            return Response::json(array(
                'Saved' => false,
                'Message'   => $e->getMessage()
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }

    public function cancelar_transferencia_entre_caja(Request $request)
    {
        try{
            if(request()->ajax())
            {
                $transferencia = tbl_transferencias_entre_cajas::get_by_id($request->transferencia_entre_caja_id);

                $transferencia->estatus = estatus_movimientos_corte::Cancelado;

                $response = tbl_transferencias_entre_cajas::edit($transferencia);
                if(!$response['saved'])
                {
                    return Response::json(array(
                        'Saved'     => false,
                        'Message'   => Lang::get('dictionary.message_an_error_occurred').' '.$response['error']
                    ));
                }

                HelperCrediuno::save_bitacora($transferencia->transferencia_entre_caja_id, movimiento_bitacora::CanceloTransferenciaEntreCajasCorte, catalago_sistema::Cortes, null, $transferencia->toJson());

                $corte = tbl_cortes::get_by_id($request->corte_id);
                $cortes = tbl_cortes::get_list_abiertos($request->corte_id)->pluck('nombre_completo_usuario', 'corte_id');
                $model = tbl_transferencias_entre_cajas::get_list_by_corte_id($request->corte_id);
                $divisas = tbl_divisas::get_list(true)->pluck('divisa', 'divisa_id');

                $html = view('cortes._transferencias_entre_caja')
                    ->with(compact('model'))
                    ->with(compact('divisas'))
                    ->with(compact('cortes'))
                    ->with(compact('corte'))
                    ->render();

                return Response::json(array(
                    'Saved'     => true,
                    'Message'   => Lang::get('dictionary.message_save_correctly'),
                    'Html'      => $html
                ));
            }
        }catch (\Exception $e){
            return Response::json(array(
                'Saved' => false,
                'Message'   => $e->getMessage()
            ));
        }

        return Response::json(array(
            'Saved' => false,
            'Message'   => Lang::get('dictionary.message_an_error_occurred')
        ));
    }
}
